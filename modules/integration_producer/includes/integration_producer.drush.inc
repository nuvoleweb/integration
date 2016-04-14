<?php

/**
 * @file
 * Contains Drush-related hook implementations.
 */

/**
 * Implements hook_drush_command().
 */
function integration_producer_drush_command() {
  $items = [];
  $items['integration-export'] = [
    'description' => 'Export data.',
    'aliases' => ['ie'],
    'arguments' => [
      'backend' => 'The backend machine name.',
      'producer' => 'The producer machine name.',
    ],
    'options' => [
      'entity-id' => [
        'description' => 'Optional entity ID.',
        'example-value' => '123',
      ],
    ],
    'examples' => [
      'drush integration-export filesystem articles --entity-id=123'
        => 'Exports article 123 using the filesystem backend and the articles producer.',
    ],
  ];
  return $items;
}

/**
 * Callback for the integration-export command.
 */
function drush_integration_producer_integration_export($backend_name, $producer_name) {
  if (!$backend_name || !$producer_name) {
    return drush_log(dt('Please specify at least a backend and a producer as arguments.'), 'error');
  }

  $producer = \Drupal\integration_producer\ProducerFactory::getInstance($producer_name);
  switch ($producer->getConfiguration()->getPlugin()) {
    case 'node_producer':
      $entity_id = (int) drush_get_option('entity-id', NULL);
      if ($entity_id) {
        $node = node_load($entity_id);
        if ($node) {
          $document = $producer->build($node);
          if ($document) {
            $backend = \Drupal\integration\Backend\BackendFactory::getInstance($backend_name);
            $document = $backend->create($producer->getConfiguration()->getResourceSchema(), $document);
            drush_log(dt('The item !id has been exported.', ['!id' => $document->getId()]), 'success');
          } else {
            drush_log(dt('The item could not be exported.'), 'error');
          }
        }
      } else {
        // @todo: export all the data provided by this producer.
      }
    break;
    // @todo: handle other plugins.
  }
}

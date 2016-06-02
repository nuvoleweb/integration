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

  $backend = \Drupal\integration\Backend\BackendFactory::getInstance($backend_name);
  $producer = \Drupal\integration_producer\ProducerFactory::getInstance($producer_name);
  $entity_id = (int) drush_get_option('entity-id', NULL);
  $entity_type = $producer->getEntityType();

  if ($entity_id) {
    $entity = entity_load_single($entity_type, $entity_id);
    if ($entity) {
      _drush_integration_producer_integration_export_entity($backend, $producer, $entity);
    }
  }
  else {
    $bundle = $producer->getConfiguration()->getEntityBundle();
    $query = new EntityFieldQuery;
    $result = $query
      ->entityCondition('entity_type', $entity_type)
      ->entityCondition('bundle', $bundle)
      ->execute();
    if (!empty($result[$entity_type])) {
      $ids = array_keys($result[$entity_type]);
      $entities = entity_load($entity_type, $ids);
      foreach($entities as $entity) {
        _drush_integration_producer_integration_export_entity($backend, $producer, $entity);
      }
    }
  }
}

/**
 * Export an entity.
 */
function _drush_integration_producer_integration_export_entity($backend, $producer, $entity) {
  $document = $producer->build($entity);
  try {
    $document = $backend->create($producer->getConfiguration()->getResourceSchema(), $document);
    drush_log(dt('The item !id has been exported.', ['!id' => $document->getId()]), 'success');
  }
  catch (\Drupal\integration\Exceptions\BaseException $e) {
    drush_log(dt('The item could not be exported.'), 'error');
    watchdog_exception("integration", $e);
  }
}

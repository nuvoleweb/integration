<?php

/**
 * @file
 * Contains Drush-related hook implementations.
 */

/**
 * Implements hook_drush_command().
 */
function integration_drush_command() {
  $items = array();
  $items['integration-export-document'] = array(
    'description' => 'Export a document.',
    'aliases' => array('iexpdoc'),
    'arguments' => array(
      'backend' => 'The backend machine name.',
      'producer' => 'The producer machine name.',
      'resource_schema' => 'The resource schema machine name.',
    ),
    'options' => array(
      'entity-id' => array(
        'description' => 'Optional entity ID.',
        'example-value' => '123',
      ),
    ),
    'examples' => array(
      'drush integration-export-document filesystem articles article --entity-id=123'
        => 'Exports article 123 using the filesystem backend and the articles producer.',
    ),
  );
  return $items;
}

/**
 * Callback for the integration-export-document command.
 */
function drush_integration_export_document($backend_name, $producer_name, $resource_schema) {
  if (!$backend_name || !$producer_name || !$resource_schema) {
    return drush_log('Please specify at least a backend, producer and schema as arguments.', 'error');
  }

  $producer = \Drupal\integration_producer\ProducerFactory::getInstance($producer_name);
  switch ($producer->getConfiguration()->getPlugin()) {
    case 'node_producer':
      $entity_id = (int) drush_get_option('entity-id', NULL);
      if ($entity_id) {
        $node = node_load($entity_id);
        if ($node) {
          $document = $producer->build($node);
        }
      }
    break;
  }

  if ($document) {
    $backend = \Drupal\integration\Backend\BackendFactory::getInstance($backend_name);
    $backend->create($resource_schema, $document);
    drush_log('Command executed.', 'success');
  } else {
    drush_log('The document could not be exported.', 'error');
  }
}

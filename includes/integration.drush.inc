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
    'aliases' => array('ied'),
    'arguments' => array(
      'backend' => 'The backend machine name.',
      'producer' => 'The producer machine name.',
    ),
    'options' => array(
      'entity-id' => array(
        'description' => 'Optional entity ID.',
        'example-value' => '123',
      ),
    ),
    'examples' => array(
      'drush integration-export-document filesystem articles --entity-id=123'
        => 'Exports article 123 using the filesystem backend and the articles producer.',
    ),
  );
  $items['integration-process-import'] = array(
    'description' => 'Process a consumer import.',
    'aliases' => array('ipi'),
    'arguments' => array(
      'consumer' => 'The consumer machine name.',
    ),
    'examples' => array(
      'drush integration-process-import articles'
        => 'Imports documents using the articles consumer.',
    ),
  );
  return $items;
}

/**
 * Callback for the integration-export-document command.
 */
function drush_integration_export_document($backend_name, $producer_name) {
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
        }
      }
    break;
  }

  if ($document) {
    $backend = \Drupal\integration\Backend\BackendFactory::getInstance($backend_name);
    $document = $backend->create($producer->getConfiguration()->getResourceSchema(), $document);
    drush_log(dt('The document !id has been exported.', array('!id' => $document->getId())), 'success');
  } else {
    drush_log(dt('The document could not be exported.'), 'error');
  }
}

/**
 * Callback for the integration-process-import command.
 */
function drush_integration_process_import($consumer_name) {
  if (!$consumer_name) {
    return drush_log(dt('Please specify a consumer as an argument.'), 'error');
  }

  $consumer = \Drupal\integration_consumer\ConsumerFactory::getInstance($consumer_name);
  $consumer->processImport();
}

<?php

/**
 * @file
 * Contains Drush-related hook implementations.
 */

/**
 * Implements hook_drush_command().
 */
function integration_consumer_drush_command() {
  $items = array();
  $items['integration-import'] = array(
    'description' => 'Import data.',
    'aliases' => array('ii'),
    'arguments' => array(
      'consumer' => 'The consumer machine name.',
    ),
    'examples' => array(
      'drush integration-import articles'
        => 'Imports data using the articles consumer.',
    ),
  );
  return $items;
}

/**
 * Callback for the integration-import command.
 */
function drush_integration_consumer_integration_import($consumer_name) {
  if (!$consumer_name) {
    return drush_log(dt('Please specify a consumer as an argument.'), 'error');
  }

  $consumer = \Drupal\integration_consumer\ConsumerFactory::getInstance($consumer_name);
  $consumer->processImport();
}

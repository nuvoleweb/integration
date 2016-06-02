<?php

/**
 * @file
 * Contains Drush-related hook implementations.
 */

/**
 * Implements hook_drush_command().
 */
function integration_consumer_drush_command() {
  $items = [];
  $items['integration-import'] = [
    'description' => 'Import data.',
    'aliases' => ['ii'],
    'arguments' => [
      'consumer' => 'The consumer machine name.',
    ],
    'examples' => [
      'drush integration-import articles'
        => 'Imports data using the articles consumer.',
    ],
  ];
  return $items;
}

/**
 * Callback for the integration-import command.
 */
function drush_integration_consumer_integration_import($consumer_name) {
  if (!$consumer_name) {
    return drush_log(dt('Please specify a consumer as an argument.'), 'error');
  }

  try {
    $consumer = \Drupal\integration_consumer\ConsumerFactory::getInstance($consumer_name);
    $consumer->processImport();
  }
  catch (\Drupal\integration\Exceptions\BaseException $e) {
    drush_log(dt('Item(s) could not be imported.'), 'error');
    watchdog_exception("integration", $e);
  }
}

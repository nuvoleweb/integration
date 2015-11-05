<?php

/**
 * @file
 * Configuration object export.
 */

$export = new \stdClass();
$export->name = 'Test configuration';
$export->machine_name = 'test_configuration';
$export->description = 'Test configuration description.';
$export->plugin = 'node_producer';
$export->entity_bundle = 'article';
$export->settings = [
  'plugin' => [
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
  ],
  'components' => [
    'field_handler' => [
      'key1' => 'value1',
      'key2' => 'value2',
      'key3' => 'value3',
    ],
  ],
];
$export->backend = 'test_configuration';
$export->resource = 'test_configuration';
$export->enabled = 1;
$export->status = 1;
$export->module = 'integration_test';

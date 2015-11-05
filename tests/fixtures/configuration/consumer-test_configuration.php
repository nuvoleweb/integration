<?php

/**
 * @file
 * Configuration object export.
 */

$export = new \stdClass();
$export->name = 'Test configuration';
$export->machine_name = 'test_configuration';
$export->description = 'Test configuration description.';
$export->plugin = 'node_consumer';
$export->entity_bundle = 'integration_test';
$export->settings = [
  'plugin' => [
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
  ],
  'components' => [
    'mapping_handler' => [
      'title' => 'title_field',
      'body' => 'body',
    ],
  ],
];
$export->backend = 'test_configuration';
$export->resource = 'test_configuration';
$export->enabled = 1;
$export->status = 1;
$export->module = 'integration_test';

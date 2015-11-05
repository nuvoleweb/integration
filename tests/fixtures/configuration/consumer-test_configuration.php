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
    'mapping' => [
      'title' => 'title_field',
      'body' => 'body',
    ],
  ],
  'components' => [],
];
$export->backend = 'test_configuration';
$export->resource = 'test_configuration';
$export->enabled = 1;
$export->status = 1;
$export->module = 'integration_test';

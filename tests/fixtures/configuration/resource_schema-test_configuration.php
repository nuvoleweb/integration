<?php

/**
 * @file
 * Configuration object export.
 */

$export = new \stdClass();
$export->name = 'Test configuration';
$export->machine_name = 'test_configuration';
$export->description = 'Test configuration description.';
$export->plugin = 'raw_resource_schema';
$export->settings = [
  'plugin' => [
    'fields' => [
      'title' => 'title',
      'body' => 'body',
      'text' => 'text',
      'images' => 'images',
      'files' => 'files',
      'dates' => 'dates',
    ],
  ],
  'components' => [],
];
$export->backend = 'test_configuration';
$export->enabled = 1;
$export->status = 1;
$export->module = 'integration_test';

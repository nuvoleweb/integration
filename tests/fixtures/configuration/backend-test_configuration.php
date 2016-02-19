<?php

/**
 * @file
 * Configuration object export.
 */

$export = new \stdClass();
$export->name = 'Test configuration';
$export->machine_name = 'test_configuration';
$export->description = 'Test configuration description.';
$export->plugin = 'memory_backend';
$export->settings = [
  'plugin' => [
    'base_url' => 'http://example.com',
    'resource_schemas' => [
      'test_configuration',
      'foo',
    ],
    'resource_schema' => [
      'test_configuration' => [
        'base_path' => 'service',
        'endpoint' => 'article',
      ],
      'foo' => [
        'base_path' => 'foo',
        'endpoint' => 'foo',
      ],
    ],
  ],
  'components' => [
    'authentication_handler' => [
      'key1' => 'value1',
      'key2' => 'value2',
      'key3' => 'value3',
    ],
  ],
];
$export->formatter = 'json_formatter';
$export->authentication = 'no_authentication';
$export->enabled = 1;
$export->status = 1;
$export->module = 'integration_test';

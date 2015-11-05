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
$export->settings = array(
  'plugin' => array(
    'base_url' => 'http://example.com',
    'resource_schema' => array(
      'test_configuration' => array(
        'base_path' => 'service',
        'endpoint' => 'article',
      ),
      'foo' => array(
        'base_path' => 'foo',
        'endpoint' => 'foo',
      ),
    ),
  ),
  'components' => array(
    'response' => array(
      'key1' => 'value1',
      'key2' => 'value2',
      'key3' => 'value3',
    ),
    'formatter' => array(
      'key1' => 'value1',
      'key2' => 'value2',
      'key3' => 'value3',
    ),
    'authentication' => array(
      'key1' => 'value1',
      'key2' => 'value2',
      'key3' => 'value3',
    ),
  ),
);
$export->response = 'raw_response';
$export->formatter = 'json_formatter';
$export->authentication = 'no_authentication';
$export->enabled = 1;
$export->status = 1;
$export->module = 'integration_test';

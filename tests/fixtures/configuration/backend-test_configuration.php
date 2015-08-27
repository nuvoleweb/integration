<?php

/**
 * @file
 * Configuration object export.
 */

$export = new \stdClass();
$export->name = 'Test configuration';
$export->machine_name = 'test_configuration';
$export->description = 'Test configuration description.';
$export->type = 'memory_backend';
$export->response = 'raw_response';
$export->formatter = 'json_formatter';
$export->module = 'integration';
$export->options = array(
  'base_path' => 'http://example.com',
  'endpoint' => 'articles',
);
$export->enabled = 1;
$export->status = 1;
$export->module = 'integration_test';

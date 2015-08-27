<?php

/**
 * @file
 * Configuration object export.
 */

$export = new \stdClass();
$export->name = 'Test configuration';
$export->machine_name = 'test_configuration';
$export->description = 'Test configuration description.';
$export->type = 'node';
$export->producer_id = 'producer-id';
$export->options = array(
  'username' => 'name',
  'password' => 'password',
);
$export->enabled = 1;
$export->status = 1;
$export->module = 'integration_test';

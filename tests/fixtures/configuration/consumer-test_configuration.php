<?php

/**
 * @file
 * Configuration object export.
 */

$export = new \stdClass();
$export->name = 'Test configuration';
$export->machine_name = 'test_configuration';
$export->description = 'Test configuration description.';
$export->backend = 'test_configuration';
$export->entity_type = 'node';
$export->entity_bundle = 'integration_test';
$export->mapping = array(
  'title' => 'title_field',
  'body' => 'body',
);
$export->options = array(
  'option1' => 'value1',
  'option2' => 'value2',
);
$export->enabled = 1;
$export->status = 1;
$export->module = 'integration_test';

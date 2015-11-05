<?php
/**
 * @file
 * integration_test.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function integration_test_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return ["version" => "1"];
  }
}

/**
 * Implements hook_node_info().
 */
function integration_test_node_info() {
  $items = [
    'integration_test' => [
      'name' => t('Integration test'),
      'base' => 'node_content',
      'description' => t('Integration test content type.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ],
  ];
  drupal_alter('node_info', $items);
  return $items;
}

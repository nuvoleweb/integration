<?php

/**
 * @file
 * Contains producer-related hook implementations.
 */

use Drupal\integration_consumer\AbstractConsumer;

/**
 * Implements hook_integration_consumer_info().
 */
function integration_consumer_integration_consumer_info() {
  return [
    'node_consumer' => [
      'label' => t('Node consumer'),
      'description' => t('Create node entities by consuming data provided by a backend.'),
      'class' => 'Drupal\integration_consumer\NodeConsumer',
      'entity type' => 'node',
    ],
  ];
}

/**
 * Implements hook_integration_consumer_components_info().
 */
function integration_consumer_integration_consumer_components_info() {
  $handlers = [
    'file_field_mapping' => [
      'label' => t('File field mapping handler'),
      'description' => t('Handle default values for field mapping sub-field.'),
      'class' => 'Drupal\integration_consumer\MappingHandler\FileFieldMappingHandler',
      'type' => 'mapping_handler',
    ],
    'text_with_summary_mapping' => [
      'label' => t('Text with summary mapping handler'),
      'description' => t('Handle "Text with summary" field type mapping.'),
      'class' => 'Drupal\integration_consumer\MappingHandler\TextWithSummaryMappingHandler',
      'type' => 'mapping_handler',
    ],
  ];
  if (module_exists('title')) {
    $handlers['title_mapping'] = [
      'label' => t('Title field mapping handler'),
      'description' => t("Make sure that both Title's module title field and title property are mapped to the same source."),
      'class' => 'Drupal\integration_consumer\MappingHandler\TitleMappingHandler',
      'type' => 'mapping_handler',
    ];
  }
  return $handlers;
}

/**
 * Implements hook_integration_consumer_migrate_pre_import().
 */
function integration_consumer_integration_consumer_migrate_pre_import(AbstractConsumer $consumer) {
  $consumer->invokePreImportEvent();
}

/**
 * Implements hook_integration_consumer_migrate_post_import().
 */
function integration_consumer_integration_consumer_migrate_post_import(AbstractConsumer $consumer) {
  $consumer->invokePostImportEvent();
}

/**
 * Implements hook_integration_consumer_migrate_pre_rollback().
 */
function integration_consumer_integration_consumer_migrate_pre_rollback(AbstractConsumer $consumer) {
  $consumer->invokePreRollbackEvent();
}

/**
 * Implements hook_integration_consumer_migrate_post_rollback().
 */
function integration_consumer_integration_consumer_migrate_post_rollback(AbstractConsumer $consumer) {
  $consumer->invokePostRollbackEvent();
}

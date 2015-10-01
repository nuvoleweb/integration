<?php

/**
 * @file
 * Contains producer-related hook implementations.
 */

/**
 * Implements hook_integration_consumer_info().
 */
function integration_consumer_integration_consumer_info() {
  return array(
    'node_consumer' => array(
      'label' => t('Node consumer'),
      'description' => t('Create node entities by consuming data provided by a backend.'),
      'class' => 'Drupal\integration_consumer\NodeConsumer',
      'entity type' => 'node',
    ),
  );
}

/**
 * Implements hook_integration_consumer_components_info().
 */
function integration_consumer_integration_consumer_components_info() {
  $handlers = array(
    'file_field_mapping' => array(
      'label' => t('File field mapping handler'),
      'description' => t('Handle default values for field mapping sub-field.'),
      'class' => 'Drupal\integration_consumer\MappingHandler\FileFieldMappingHandler',
      'type' => 'mapping_handler',
    ),
    'text_with_summary_mapping' => array(
      'label' => t('Text with summary mapping handler'),
      'description' => t('Handle "Text with summary" field type mapping.'),
      'class' => 'Drupal\integration_consumer\MappingHandler\TextWithSummaryMappingHandler',
      'type' => 'mapping_handler',
    ),
  );
  if (module_exists('title')) {
    $handlers['title_mapping'] = array(
      'label' => t('Title field mapping handler'),
      'description' => t("Make sure that both Title's module title field and title property are mapped to the same source."),
      'class' => 'Drupal\integration_consumer\MappingHandler\TitleMappingHandler',
      'type' => 'mapping_handler',
    );
  }
  return $handlers;
}

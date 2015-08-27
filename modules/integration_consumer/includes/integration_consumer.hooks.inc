<?php

/**
 * @file
 * Contains producer-related hook implementations.
 */

/**
 * Implements hook_integration_consumer_mapping_handler_info().
 */
function integration_consumer_integration_consumer_mapping_handler_info() {
  $handlers = array(
    'file_field_mapping' => array(
      'label' => t('File field mapping handler'),
      'description' => t('Handle default values for field mapping sub-field.'),
      'class' => 'Drupal\integration\Consumer\MappingHandler\FileFieldMappingHandler',
    ),
    'text_with_summary_mapping' => array(
      'label' => t('Text with summary mapping handler'),
      'description' => t('Handle "Text with summary" field type mapping.'),
      'class' => 'Drupal\integration\Consumer\MappingHandler\TextWithSummaryMappingHandler',
    ),
  );
  if (module_exists('title')) {
    $handlers['title_mapping'] = array(
      'label' => t('Title field mapping handler'),
      'description' => t("Make sure that both Title's module title field and title property are mapped to the same source."),
      'class' => 'Drupal\integration\Consumer\MappingHandler\TitleMappingHandler',
    );
  }
  return $handlers;
}

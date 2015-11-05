<?php

/**
 * @file
 * Contains producer-related hook implementations.
 */

/**
 * Implements hook_integration_producer_info().
 */
function integration_producer_integration_producer_info() {
  return [
    'node_producer' => [
      'label' => t('Node producer'),
      'description' => t('Convert node entities into integration layer documents.'),
      'class' => 'Drupal\integration_producer\NodeProducer',
      'entity type' => 'node',
    ],
    'taxonomy_term_producer' => [
      'label' => t('Taxonomy term producer'),
      'description' => t('Convert taxonomy term entities into integration layer documents.'),
      'class' => 'Drupal\integration_producer\TaxonomyTermProducer',
      'entity type' => 'taxonomy_term',
    ],
  ];
}

/**
 * Implements hook_integration_producer_components_info().
 */
function integration_producer_integration_producer_components_info() {
  return [
    'default' => [
      'label' => t('Default field handler'),
      'description' => t('Not supported field types will fallback on the default field handler.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\DefaultFieldHandler',
      'type' => 'field_handler',
    ],
    'text' => [
      'label' => t('Text field handler'),
      'description' => t('Handler for "text" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\TextFieldHandler',
      'type' => 'field_handler',
    ],
    'text_long' => [
      'label' => t('Long text field handler'),
      'description' => t('Handler for "long text" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\TextFieldHandler',
      'type' => 'field_handler',
    ],
    'text_with_summary' => [
      'label' => t('Long text with summary field handler'),
      'description' => t('Handler for "text with summary" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\TextWithSummaryFieldHandler',
      'type' => 'field_handler',
    ],
    'date' => [
      'label' => t('Date field handler'),
      'description' => t('Handler for "date" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\DateFieldHandler',
      'type' => 'field_handler',
    ],
    'datetime' => [
      'label' => t('Datetime field handler'),
      'description' => t('Handler for "datetime" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\DateFieldHandler',
      'type' => 'field_handler',
    ],
    'datestamp' => [
      'label' => t('Datestamp field handler'),
      'description' => t('Handler for "datestamp" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\DateFieldHandler',
      'type' => 'field_handler',
    ],
    'file' => [
      'label' => t('file field handler'),
      'description' => t('Handler for "file" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\FileFieldHandler',
      'type' => 'field_handler',
    ],
    'image' => [
      'label' => t('Image field handler'),
      'description' => t('Handler for "image" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\ImageFieldHandler',
      'type' => 'field_handler',
    ],
  ];
}

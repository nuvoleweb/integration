<?php

/**
 * @file
 * Contains producer-related hook implementations.
 */

/**
 * Implements hook_integration_producer_info().
 */
function integration_producer_integration_producer_info() {
  return array(
    'node' => array(
      'label' => t('Node producer'),
      'description' => t('Produces node entities.'),
      'class' => 'Drupal\integration\Producer\NodeProducer',
    ),
    'taxonomy_term' => array(
      'label' => t('Taxonomy term producer'),
      'description' => t('Produces taxonomy term entities.'),
      'class' => 'Drupal\integration\Producer\TaxonomyTermProducer',
    ),
  );
}

/**
 * Implements hook_integration_producer_field_handler_info().
 */
function integration_producer_integration_producer_field_handler_info() {
  return array(
    'default' => array(
      'label' => t('Default field handler'),
      'description' => t('Not supported field types will fallback on the default field handler.'),
      'class' => 'Drupal\integration\Producer\FieldHandlers\DefaultFieldHandler',
    ),
    'text' => array(
      'label' => t('Text field handler'),
      'description' => t('Handler for "text" field type.'),
      'class' => 'Drupal\integration\Producer\FieldHandlers\TextFieldHandler',
    ),
    'text_long' => array(
      'label' => t('Long text field handler'),
      'description' => t('Handler for "long text" field type.'),
      'class' => 'Drupal\integration\Producer\FieldHandlers\TextFieldHandler',
    ),
    'text_with_summary' => array(
      'label' => t('Long text with summary field handler'),
      'description' => t('Handler for "text with summary" field type.'),
      'class' => 'Drupal\integration\Producer\FieldHandlers\TextWithSummaryFieldHandler',
    ),
    'date' => array(
      'label' => t('Date field handler'),
      'description' => t('Handler for "date" field type.'),
      'class' => 'Drupal\integration\Producer\FieldHandlers\DateFieldHandler',
    ),
    'datetime' => array(
      'label' => t('Datetime field handler'),
      'description' => t('Handler for "datetime" field type.'),
      'class' => 'Drupal\integration\Producer\FieldHandlers\DateFieldHandler',
    ),
    'datestamp' => array(
      'label' => t('Datestamp field handler'),
      'description' => t('Handler for "datestamp" field type.'),
      'class' => 'Drupal\integration\Producer\FieldHandlers\DateFieldHandler',
    ),
    'file' => array(
      'label' => t('file field handler'),
      'description' => t('Handler for "file" field type.'),
      'class' => 'Drupal\integration\Producer\FieldHandlers\FileFieldHandler',
    ),
    'image' => array(
      'label' => t('Image field handler'),
      'description' => t('Handler for "image" field type.'),
      'class' => 'Drupal\integration\Producer\FieldHandlers\ImageFieldHandler',
    ),
  );
}

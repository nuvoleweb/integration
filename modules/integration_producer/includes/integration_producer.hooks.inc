<?php

/**
 * @file
 * Contains producer-related hook implementations.
 */

/**
 * Implements hook_integration_plugins().
 */
function integration_producer_integration_plugins() {
  return array(
    'producer' => array(
      'label' => t('Producer'),
      'components' => array(
        'field_handler' => array(
          'label' => t('Field handler'),
        ),
      ),
    ),
  );
}

/**
 * Implements hook_integration_producer_info().
 */
function integration_producer_integration_producer_info() {
  return array(
    'node_producer' => array(
      'label' => t('Node producer'),
      'description' => t('Convert node entities into integration layer documents.'),
      'class' => 'Drupal\integration_producer\NodeProducer',
      'entity type' => 'node',
    ),
    'taxonomy_term_producer' => array(
      'label' => t('Taxonomy term producer'),
      'description' => t('Convert taxonomy term entities into integration layer documents.'),
      'class' => 'Drupal\integration_producer\TaxonomyTermProducer',
      'entity type' => 'taxonomy_term',
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
      'class' => 'Drupal\integration_producer\FieldHandlers\DefaultFieldHandler',
    ),
    'text' => array(
      'label' => t('Text field handler'),
      'description' => t('Handler for "text" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\TextFieldHandler',
    ),
    'text_long' => array(
      'label' => t('Long text field handler'),
      'description' => t('Handler for "long text" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\TextFieldHandler',
    ),
    'text_with_summary' => array(
      'label' => t('Long text with summary field handler'),
      'description' => t('Handler for "text with summary" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\TextWithSummaryFieldHandler',
    ),
    'date' => array(
      'label' => t('Date field handler'),
      'description' => t('Handler for "date" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\DateFieldHandler',
    ),
    'datetime' => array(
      'label' => t('Datetime field handler'),
      'description' => t('Handler for "datetime" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\DateFieldHandler',
    ),
    'datestamp' => array(
      'label' => t('Datestamp field handler'),
      'description' => t('Handler for "datestamp" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\DateFieldHandler',
    ),
    'file' => array(
      'label' => t('file field handler'),
      'description' => t('Handler for "file" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\FileFieldHandler',
    ),
    'image' => array(
      'label' => t('Image field handler'),
      'description' => t('Handler for "image" field type.'),
      'class' => 'Drupal\integration_producer\FieldHandlers\ImageFieldHandler',
    ),
  );
}

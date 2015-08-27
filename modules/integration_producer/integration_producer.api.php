<?php

/**
 * @file
 * Contains API documentation.
 */

use Drupal\integration\Document\DocumentInterface;
use Drupal\integration\Producer\EntityWrapper\EntityWrapper;

/**
 * Implements hook_integration_producer_info().
 */
function hook_integration_producer_info() {
  return array(
    'node' => array(
      'class' => '\Drupal\integration\Producer\DefaultProducer',
    ),
  );
}

/**
 * Implements hook_integration_producer_info_alter().
 */
function hook_integration_producer_info_alter(&$producers) {
  $producers['node']['class'] = '\Drupal\custom\Producer\CustomProducer';
}

/**
 * Implements hook_integration_producer_field_handler_info().
 */
function hook_integration_producer_field_handler_info() {
  return array(
    'text' => array(
      'class' => '\Drupal\integration\Producer\FieldHandlers\TextFieldHandler',
      'alter' => TRUE,
    ),
  );
}

/**
 * Implements hook_integration_producer_field_handler_info_alter().
 */
function hook_integration_producer_field_handler_info_alter(&$field_handlers) {
  $field_handlers['alter'] = FALSE;
}

/**
 * Implements hook_integration_producer_document_build_alter().
 */
function hook_integration_producer_document_build_alter(EntityWrapper $entity_wrapper, DocumentInterface $document) {
  if ($entity_wrapper->type() == 'node') {
    $document->setMetadata('original-type', $entity_wrapper->getBundle());
  }
}

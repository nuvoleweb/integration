<?php

/**
 * @file
 * Contains Entity-related hook implementations.
 */

/**
 * Implements hook_entity_info().
 */
function integration_producer_entity_info() {

  $info['integration_producer'] = array(
    'label' => t('Integration layer producer'),
    'entity class' => 'Drupal\integration_producer\Configuration\ProducerConfiguration',
    'base table' => 'integration_producer',
    'access callback' => 'integration_producer_access',
    'module' => 'integration_producer',
  ) + integration_entity_info_defaults();

  return $info;
}

/**
 * Implements hook_entity_property_info().
 */
function integration_producer_entity_property_info() {

  $info['integration_producer']['properties'] = integration_entity_property_info_defaults() + array(
    'type' => array(
      'label' => t('Producer type'),
      'type' => 'text',
      'description' => t('Producer plugin ID, as returned by hook_integration_producer_info() implementations.'),
      'schema field' => 'type',
      'required' => TRUE,
    ),
  );

  return $info;
}

<?php

/**
 * @file
 * Contains Entity-related hook implementations.
 */

/**
 * Implements hook_entity_info().
 */
function integration_consumer_entity_info() {

  $info['integration_consumer'] = array(
    'label' => t('Integration layer consumer'),
    'entity class' => 'Drupal\integration_consumer\Configuration\ConsumerConfiguration',
    'base table' => 'integration_consumer',
    'access callback' => 'integration_consumer_access',
    'module' => 'integration_consumer',
  ) + integration_entity_info_defaults();

  return $info;
}

/**
 * Implements hook_entity_property_info().
 */
function integration_consumer_entity_property_info() {

  $info['integration_consumer']['properties'] = integration_entity_property_info_defaults() + array(
    'backend' => array(
      'label' => t('Backend'),
      'type' => 'integration_backend',
      'description' => t('The ID of the consumer plugin to use.'),
      'schema field' => 'backend',
      'required' => TRUE,
    ),
    'entity_type' => array(
      'label' => t('Entity type'),
      'type' => 'text',
      'description' => t('Entity type machine name.'),
      'schema field' => 'entity_type',
      'required' => TRUE,
    ),
    'entity_bundle' => array(
      'label' => t('Entity bundle'),
      'type' => 'text',
      'description' => t('Entity bundle machine name.'),
      'schema field' => 'entity_bundle',
      'required' => TRUE,
    ),
  );

  return $info;
}

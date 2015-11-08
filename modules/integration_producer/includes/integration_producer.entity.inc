<?php

/**
 * @file
 * Contains Entity-related hook implementations.
 */

/**
 * Implements hook_entity_info().
 */
function integration_producer_entity_info() {

  $info['integration_producer'] = [
    'label' => t('Producer'),
    'entity class' => 'Drupal\integration_producer\Configuration\ProducerConfiguration',
    'base table' => 'integration_producer',
    'access callback' => 'integration_producer_access',
    'module' => 'integration_producer',
  ] + integration_entity_info_defaults();

  return $info;
}

/**
 * Implements hook_entity_property_info().
 */
function integration_producer_entity_property_info() {

  $info['integration_producer']['properties'] = integration_entity_property_info_defaults() + [
    'entity_bundle' => [
      'label' => t('Entity bundle'),
      'type' => 'text',
      'description' => t('Entity bundle machine name.'),
      'schema field' => 'entity_bundle',
      'required' => TRUE,
    ],
    'backend' => [
      'label' => t('Backend'),
      'type' => 'integration_backend',
      'description' => t('Machine name of the backend configuration to use.'),
      'schema field' => 'backend',
      'required' => TRUE,
    ],
    'resource' => [
      'label' => t('Resource'),
      'type' => 'integration_resource_schema',
      'description' => t('Machine name of the resource schema configuration to use.'),
      'schema field' => 'resource',
      'required' => TRUE,
    ],
  ];

  return $info;
}

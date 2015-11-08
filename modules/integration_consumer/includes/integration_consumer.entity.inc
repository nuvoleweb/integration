<?php

/**
 * @file
 * Contains Entity-related hook implementations.
 */

/**
 * Implements hook_entity_info().
 */
function integration_consumer_entity_info() {

  $info['integration_consumer'] = [
    'label' => t('Consumer'),
    'entity class' => 'Drupal\integration_consumer\Configuration\ConsumerConfiguration',
    'base table' => 'integration_consumer',
    'access callback' => 'integration_consumer_access',
    'module' => 'integration_consumer',
  ] + integration_entity_info_defaults();

  return $info;
}

/**
 * Implements hook_entity_property_info().
 */
function integration_consumer_entity_property_info() {

  $info['integration_consumer']['properties'] = integration_entity_property_info_defaults() + [
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

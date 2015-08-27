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
    'controller class' => 'EntityAPIControllerExportable',
    'metadata controller class' => FALSE,
    'entity class' => 'Drupal\integration\Consumer\Configuration\ConsumerConfiguration',
    'base table' => 'integration_consumer',
    'uri callback' => 'integration_consumer_url',
    'access callback' => 'integration_consumer_access',
    'module' => 'integration_consumer',
    'exportable' => TRUE,
    'entity keys' => array(
      'id' => 'id',
      'label' => 'name',
      'name' => 'machine_name',
    ),
  );
  return $info;
}

/**
 * Implements hook_entity_property_info().
 */
function integration_consumer_entity_property_info() {
  $info['integration_consumer']['properties'] = array(
    'id' => array(
      'label' => t('ID'),
      'type' => 'integer',
      'description' => t('The primary identifier for a consumer.'),
      'schema field' => 'id',
      'validation callback' => 'entity_metadata_validate_integer_positive',
    ),
    'name' => array(
      'label' => t('Name'),
      'type' => 'text',
      'description' => t('The displayed name for a consumer.'),
      'schema field' => 'name',
      'required' => TRUE,
    ),
    'machine_name' => array(
      'label' => t('Machine name'),
      'type' => 'token',
      'description' => t('The machine name for a consumer.'),
      'schema field' => 'machine_name',
      'required' => TRUE,
    ),
    'description' => array(
      'label' => t('Description'),
      'type' => 'text',
      'description' => t('The displayed description for a consumer.'),
      'schema field' => 'description',
      'sanitize' => 'filter_xss',
    ),
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
    'enabled' => array(
      'label' => t('Enabled'),
      'type' => 'boolean',
      'description' => t('A flag indicating whether the consumer is enabled.'),
      'schema field' => 'enabled',
    ),
    'status' => array(
      'label' => t('Status'),
      'type' => 'integer',
      'description' => t('The exportable status of the consumer.'),
      'schema field' => 'status',
      'options list' => 'integration_configuration_status_options_list',
    ),
    'module' => array(
      'label' => t('Module'),
      'type' => 'text',
      'description' => t('The name of the module from which this consumer originates.'),
      'schema field' => 'module',
    ),
  );
  return $info;
}

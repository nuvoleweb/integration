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
    'controller class' => 'EntityAPIControllerExportable',
    'metadata controller class' => FALSE,
    'entity class' => 'Drupal\integration\Producer\Configuration\ProducerConfiguration',
    'base table' => 'integration_producer',
    'uri callback' => 'integration_producer_url',
    'access callback' => 'integration_producer_access',
    'module' => 'integration',
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
function integration_producer_entity_property_info() {
  $info['integration_producer']['properties'] = array(
    'id' => array(
      'label' => t('ID'),
      'type' => 'integer',
      'description' => t('The primary identifier for a producer.'),
      'schema field' => 'id',
      'validation callback' => 'entity_metadata_validate_integer_positive',
    ),
    'name' => array(
      'label' => t('Name'),
      'type' => 'text',
      'description' => t('The displayed name for a producer.'),
      'schema field' => 'name',
      'required' => TRUE,
    ),
    'machine_name' => array(
      'label' => t('Machine name'),
      'type' => 'token',
      'description' => t('The machine name for a producer.'),
      'schema field' => 'machine_name',
      'required' => TRUE,
    ),
    'description' => array(
      'label' => t('Description'),
      'type' => 'text',
      'description' => t('The displayed description for a producer.'),
      'schema field' => 'description',
      'sanitize' => 'filter_xss',
    ),
    'type' => array(
      'label' => t('Producer type'),
      'type' => 'text',
      'description' => t('The id of the producer plugin to use.'),
      'schema field' => 'type',
      'required' => TRUE,
    ),
    'producer_id' => array(
      'label' => t('Producer ID'),
      'type' => 'text',
      'description' => t('The ID of the producer.'),
      'schema field' => 'producer_id',
      'required' => TRUE,
    ),
    'enabled' => array(
      'label' => t('Enabled'),
      'type' => 'boolean',
      'description' => t('A flag indicating whether the producer is enabled.'),
      'schema field' => 'enabled',
    ),
    'status' => array(
      'label' => t('Status'),
      'type' => 'integer',
      'description' => t('The exportable status of the producer.'),
      'schema field' => 'status',
      'options list' => 'integration_configuration_status_options_list',
    ),
    'module' => array(
      'label' => t('Module'),
      'type' => 'text',
      'description' => t('The name of the module from which this producer originates.'),
      'schema field' => 'module',
    ),
  );
  return $info;
}

<?php

/**
 * @file
 * Contains Entity-related hook implementations.
 */

/**
 * Implements hook_entity_info().
 */
function integration_entity_info() {
  $info['integration_backend'] = array(
    'label' => t('Integration layer backend'),
    'controller class' => 'EntityAPIControllerExportable',
    'metadata controller class' => FALSE,
    'entity class' => 'Drupal\integration\Backend\Configuration\BackendConfiguration',
    'base table' => 'integration_backend',
    'uri callback' => 'integration_backend_url',
    'access callback' => 'integration_backend_access',
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
function integration_entity_property_info() {
  $info['integration_backend']['properties'] = array(
    'id' => array(
      'label' => t('ID'),
      'type' => 'integer',
      'description' => t('The primary identifier for a backend.'),
      'schema field' => 'id',
      'validation callback' => 'entity_metadata_validate_integer_positive',
    ),
    'name' => array(
      'label' => t('Name'),
      'type' => 'text',
      'description' => t('The displayed name for a backend.'),
      'schema field' => 'name',
      'required' => TRUE,
    ),
    'machine_name' => array(
      'label' => t('Machine name'),
      'type' => 'token',
      'description' => t('The machine name for a backend.'),
      'schema field' => 'machine_name',
      'required' => TRUE,
    ),
    'description' => array(
      'label' => t('Description'),
      'type' => 'text',
      'description' => t('The displayed description for a backend.'),
      'schema field' => 'description',
      'sanitize' => 'filter_xss',
    ),
    'type' => array(
      'label' => t('Backend type'),
      'type' => 'text',
      'description' => t('The ID of the backend plugin to use.'),
      'schema field' => 'type',
      'required' => TRUE,
    ),
    'response' => array(
      'label' => t('Response'),
      'type' => 'text',
      'description' => t('The ID of the response plugin to use.'),
      'schema field' => 'response',
      'required' => TRUE,
    ),
    'formatter' => array(
      'label' => t('Formatter'),
      'type' => 'text',
      'description' => t('The ID of the response plugin to use.'),
      'schema field' => 'formatter',
      'required' => TRUE,
    ),
    'enabled' => array(
      'label' => t('Enabled'),
      'type' => 'boolean',
      'description' => t('A flag indicating whether the backend is enabled.'),
      'schema field' => 'enabled',
    ),
    'status' => array(
      'label' => t('Status'),
      'type' => 'integer',
      'description' => t('The exportable status of the backend.'),
      'schema field' => 'status',
      'options list' => 'integration_configuration_status_options_list',
    ),
    'module' => array(
      'label' => t('Module'),
      'type' => 'text',
      'description' => t('The name of the module from which this backend originates.'),
      'schema field' => 'module',
    ),
  );
  return $info;
}

/**
 * Returns an options list for the "status" property.
 *
 * Options list callback used in hook_entity_property_info().
 *
 * @return array
 *   An array of options, as defined by hook_options_list().
 */
function integration_configuration_status_options_list() {
  return array(
    ENTITY_CUSTOM => t('Custom'),
    ENTITY_IN_CODE => t('Default'),
    ENTITY_OVERRIDDEN => t('Overridden'),
    ENTITY_FIXED => t('Fixed'),
  );
}

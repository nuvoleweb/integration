<?php

/**
 * @file
 * Contains Entity-related hook implementations.
 */

/**
 * Implements hook_entity_info().
 */
function integration_entity_info() {

  $info['integration_backend'] = [
    'label' => t('Backend'),
    'entity class' => 'Drupal\integration\Backend\Configuration\BackendConfiguration',
    'base table' => 'integration_backend',
    'access callback' => 'integration_backend_access',
    'module' => 'integration',
  ] + integration_entity_info_defaults();

  $info['integration_backend_entity'] = [
    'label' => t('Backend entities'),
    'base table' => 'integration_backend_entities',
    'controller class' => 'Drupal\integration\Backend\Entity\BackendEntityController',
    'entity keys' => [
      'id' => 'id',
    ],
    'module' => 'integration',
  ];

  $info['integration_resource_schema'] = [
    'label' => t('Resource schema'),
    'entity class' => 'Drupal\integration\ResourceSchema\Configuration\ResourceSchemaConfiguration',
    'base table' => 'integration_resource_schema',
    'access callback' => 'integration_backend_access',
    'module' => 'integration',
  ] + integration_entity_info_defaults();

  return $info;
}

/**
 * Implements hook_entity_property_info().
 */
function integration_entity_property_info() {

  $info['integration_backend']['properties'] = integration_entity_property_info_defaults() + [
    'formatter' => [
      'label' => t('Formatter'),
      'type' => 'text',
      'description' => t('Formatter plugin name.'),
      'schema field' => 'formatter',
      'required' => TRUE,
    ],
    'authentication' => [
      'label' => t('Authentication'),
      'type' => 'text',
      'description' => t('Authentication plugin name.'),
      'schema field' => 'authentication',
      'required' => TRUE,
    ],
  ];

  $info['integration_resource_schema']['properties'] = integration_entity_property_info_defaults();

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
  return [
    ENTITY_CUSTOM => t('Custom'),
    ENTITY_IN_CODE => t('Default'),
    ENTITY_OVERRIDDEN => t('Overridden'),
    ENTITY_FIXED => t('Fixed'),
  ];
}

/**
 * Provides sensitive defaults for hook_entity_info() implementations.
 *
 * @return array
 *    Array of configuration entity info values.
 *
 * @see integration_entity_info()
 * @see integration_consumer_entity_info()
 * @see integration_producer_entity_info()
 */
function integration_entity_info_defaults() {
  return [
    'controller class' => 'EntityAPIControllerExportable',
    'metadata controller class' => FALSE,
    'uri callback' => 'integration_uri_callback',
    'exportable' => TRUE,
    'entity keys' => [
      'id' => 'id',
      'label' => 'name',
      'name' => 'machine_name',
    ],
  ];
}

/**
 * Provides sensitive defaults for hook_entity_property_info() implementations.
 *
 * @return array
 *    Array of configuration entity property default values.
 *
 * @see integration_entity_property_info()
 * @see integration_consumer_entity_property_info()
 * @see integration_producer_entity_property_info()
 */
function integration_entity_property_info_defaults() {
  return [
    'id' => [
      'label' => t('ID'),
      'type' => 'integer',
      'description' => t('Primary identifier.'),
      'schema field' => 'id',
      'validation callback' => 'entity_metadata_validate_integer_positive',
    ],
    'name' => [
      'label' => t('Name'),
      'type' => 'text',
      'description' => t('Human readable configuration name.'),
      'schema field' => 'name',
      'required' => TRUE,
    ],
    'machine_name' => [
      'label' => t('Machine name'),
      'type' => 'token',
      'description' => t('Configuration machine name.'),
      'schema field' => 'machine_name',
      'required' => TRUE,
    ],
    'description' => [
      'label' => t('Description'),
      'type' => 'text',
      'description' => t('Configuration entity description.'),
      'schema field' => 'description',
      'sanitize' => 'filter_xss',
    ],
    'plugin' => [
      'label' => t('Plugin'),
      'type' => 'text',
      'description' => t('Plugin name, as returned by hook_integration_PLUGIN_TYPE_info() implementations.'),
      'schema field' => 'plugin',
      'required' => TRUE,
    ],
    'settings' => [
      'label' => t('Settings'),
      'description' => t('Configuration entity settings'),
      'type' => 'struct',
      'schema field' => 'settings',
      'property info' => [
        'plugin' => [
          'label' => t('Plugin settings'),
          'description' => t('Main plugin setting array.'),
          'type' => 'struct',
        ],
        'components' => [
          'label' => t('Components settings'),
          'description' => t('Array plugin components setting arrays, keyed by component name as defined in hook_integration_plugins().'),
          'type' => 'struct',
        ],
      ],
    ],
    'enabled' => [
      'label' => t('Enabled'),
      'type' => 'boolean',
      'description' => t('A flag indicating whether the configuration is enabled.'),
      'schema field' => 'enabled',
    ],
    'status' => [
      'label' => t('Status'),
      'type' => 'integer',
      'description' => t('The exportable status of the configuration entity.'),
      'schema field' => 'status',
      'options list' => 'integration_configuration_status_options_list',
    ],
    'module' => [
      'label' => t('Module'),
      'type' => 'text',
      'description' => t('The name of the module from which this configuration entity originates.'),
      'schema field' => 'module',
    ],
  ];
}

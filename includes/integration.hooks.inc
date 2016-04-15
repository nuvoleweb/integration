<?php

/**
 * @file
 * Contains backend-related hook implementations.
 */

/**
 * Implements hook_integration_backend_info().
 */
function integration_integration_backend_info() {
  return [
    'memory_backend' => [
      'label' => t('Memory backend'),
      'description' => t('Backend using memory to store data, primarily used for testing.'),
      'class' => 'Drupal\integration\Backend\MemoryBackend',
    ],
    'filesystem_backend' => [
      'label' => t('File system backend'),
      'description' => t('Backend using local filesystem as a storage.'),
      'class' => 'Drupal\integration\Backend\FileSystemBackend',
    ],
  ];
}

/**
 * Implements hook_integration_backend_components_info().
 */
function integration_integration_backend_components_info() {
  return [
    'json_formatter' => [
      'label' => t('JSON Formatter'),
      'description' => t('Format documents in JSON format'),
      'class' => 'Drupal\integration\Backend\Formatter\JsonFormatter',
      'type' => 'formatter_handler',
    ],
    'http_authentication' => [
      'label' => t('HTTP Authentication'),
      'description' => t('Provide basic HTTP authentication.'),
      'class' => 'Drupal\integration\Backend\Authentication\HttpAuthentication',
      'type' => 'authentication_handler',
    ],
    'no_authentication' => [
      'label' => t('No authentication'),
      'description' => t('Backend can be accessed without restrictions.'),
      'class' => 'Drupal\integration\Backend\Authentication\NoAuthentication',
      'plugin types' => ['memory_backend'],
      'type' => 'authentication_handler',
    ],
  ];
}

/**
 * Implements hook_integration_resource_schema_info().
 */
function integration_integration_resource_schema_info() {
  return [
    'raw_resource_schema' => [
      'label' => t('Raw resource schema'),
      'description' => t('Specify a simple list of fields, without any validation.'),
      'class' => 'Drupal\integration\ResourceSchema\RawResourceSchema',
    ],
  ];
}

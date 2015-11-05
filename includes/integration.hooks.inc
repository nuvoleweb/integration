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
    'rest_backend' => [
      'label' => t('REST backend'),
      'description' => t('Backend implementing a RESTful calls in order to store data remotely.'),
      'class' => 'Drupal\integration\Backend\RestBackend',
    ],
    'memory_backend' => [
      'label' => t('Memory backend'),
      'description' => t('Backend using memory to store data, primarily used for testing.'),
      'class' => 'Drupal\integration\Backend\MemoryBackend',
    ],
    'file_system_backend' => [
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
    'http_response' => [
      'label' => t('HTTP request response'),
      'description' => t('Parse response returned by standard, non-overridden, drupal_http_request().'),
      'class' => 'Drupal\integration\Backend\Response\HttpRequestResponse',
      'type' => 'response_handler',
    ],
    'raw_response' => [
      'label' => t('Raw response'),
      'description' => t('Simply passes along whatever returned by the backend in use.'),
      'class' => 'Drupal\integration\Backend\Response\RawResponse',
      'type' => 'response_handler',
    ],
    'http_authentication' => [
      'label' => t('HTTP Authentication'),
      'description' => t('Provide basic HTTP authentication.'),
      'class' => 'Drupal\integration\Backend\Authentication\HttpAuthentication',
      'plugin types' => ['rest_backend'],
      'type' => 'authentication_handler',
    ],
    'no_authentication' => [
      'label' => t('No authentication'),
      'description' => t('Backend can be accessed without restrictions.'),
      'class' => 'Drupal\integration\Backend\Authentication\NoAuthentication',
      'plugin types' => ['rest_backend', 'memory_backend'],
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

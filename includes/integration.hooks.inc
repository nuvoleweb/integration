<?php

/**
 * @file
 * Contains backend-related hook implementations.
 */

/**
 * Implements hook_integration_backend_info().
 */
function integration_integration_backend_info() {
  return array(
    'rest_backend' => array(
      'label' => t('REST backend'),
      'description' => t('Backend implementing a RESTful calls in order to store data remotely.'),
      'class' => 'Drupal\integration\Backend\RestBackend',
    ),
    'memory_backend' => array(
      'label' => t('Memory backend'),
      'description' => t('Backend using memory to store data, primarily used for testing.'),
      'class' => 'Drupal\integration\Backend\MemoryBackend',
    ),
  );
}

/**
 * Implements hook_integration_backend_formatter_handler_info().
 */
function integration_integration_backend_formatter_handler_info() {
  return array(
    'json_formatter' => array(
      'label' => t('JSON Formatter'),
      'description' => t('Format documents in JSON format'),
      'class' => 'Drupal\integration\Backend\Formatter\JsonFormatter',
    ),
  );
}

/**
 * Implements hook_integration_backend_response_handler_info().
 */
function integration_integration_backend_response_handler_info() {
  return array(
    'http_response' => array(
      'label' => t('HTTP request response'),
      'description' => t('Parse response returned by standard, non-overridden, drupal_http_request().'),
      'class' => 'Drupal\integration\Backend\Response\HttpRequestResponse',
    ),
    'raw_response' => array(
      'label' => t('Raw response'),
      'description' => t('Simply passes along whatever returned by the backend in use.'),
      'class' => 'Drupal\integration\Backend\Response\RawResponse',
    ),
  );
}

/**
 * Implements hook_integration_backend_authentication_handler_info().
 */
function integration_integration_backend_authentication_handler_info() {
  return array(
    'http_authentication' => array(
      'label' => t('HTTP Authentication'),
      'description' => t('Provide basic HTTP authentication.'),
      'class' => 'Drupal\integration\Backend\Authentication\HttpAuthentication',
      'configuration class' => 'Drupal\integration\Backend\Authentication\HttpAuthenticationConfiguration',
      'plugin types' => array('rest_backend'),
    ),
    'no_authentication' => array(
      'label' => t('No authentication'),
      'description' => t('Backend can be accessed without restrictions.'),
      'class' => 'Drupal\integration\Backend\Authentication\NoAuthentication',
      'plugin types' => array('rest_backend', 'memory_backend'),
    ),
  );
}

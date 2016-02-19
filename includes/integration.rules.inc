<?php

/**
 * @file
 * Contains Rules-related hook implementations.
 */

/**
 * Implements hook_rules_action_info().
 */
function integration_rules_data_info() {
  return [
    'integration_document' => [
      'label' => t('document'),
      'group' => t('Integration'),
    ],
    'integration_resource_schema' => [
      'label' => t('resource schema'),
      'group' => t('Integration'),
    ],
  ];
}

/**
 * Implementation of hook_rules_action_info().
 */
function integration_rules_action_info() {
  return [
    'integration_rules_action_backend_create_document' => [
      'label' => t('Backend: Push a document'),
      'group' => t('Integration'),
      'parameter' => [
        'backend' => [
          'type' => 'token',
          'label' => t('Backend'),
          'options list' => 'integration_rules_get_backend_list',
          'optional' => FALSE,
        ],
        'resource_schema' => [
          'type' => 'integration_resource_schema',
          'label' => t('Resource schema'),
        ],
        'document' => [
          'type' => 'integration_document',
          'label' => t('Document'),
        ],
      ],
      'provides' => [
        'integration_document' => [
          'label' => t('Document'),
          'type' => 'integration_document',
        ],
      ],
      'module' => 'integration',
    ],
  ];
}

/**
 * Create a document with given parameters.
 *
 * @param string $backend_name
 *    Backend machine name.
 * @param \Drupal\integration\ResourceSchema\Configuration\ResourceSchemaConfiguration $resource_schema
 *    Resource schema for which to create the
 * @param \Drupal\integration\Document\DocumentInterface $document
 *    Document object
 *
 * @return array
 *    Rules variable consisting of the built Document object.
 */
function integration_rules_action_backend_create_document($backend_name, $resource_schema, $document) {
  $return = ['document' => NULL];
  try {
    $backend = \Drupal\integration\Backend\BackendFactory::getInstance($backend_name);
    $return = ['document' => $backend->create($resource_schema->getMachineName(), $document)];
  }
  catch (\Drupal\integration\Exceptions\BaseException $e) {
    rules_log($e->getMessage(), [], RulesLog::ERROR);
  }
  return $return;
}

/**
 * Return backend list.
 *
 * @return array
 *    List of suitable producer plugins.
 */
function integration_rules_get_backend_list() {
  $options = [];
  foreach (entity_load('integration_backend') as $producer) {
    /** @var \Drupal\integration\Configuration\AbstractConfiguration $producer */
    $options[$producer->getMachineName()] = $producer->getName();
  }
  return $options;
}

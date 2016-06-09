<?php

/**
 * @file
 * Contains Rules-related hook implementations.
 */

/**
 * Implements hook_rules_event_info().
 */
function integration_consumer_rules_event_info() {
  return [
    'integration_consumer_pre_import' => [
      'label' => t('Consumer: Before import'),
      'group' => t('Integration'),
      'module' => 'integration',
      'variables' => [
        'consumer' => [
          'type' => 'integration_consumer',
          'label' => t('Integration: Consumer'),
        ],
      ],
    ],
    'integration_consumer_post_import' => [
      'label' => t('Consumer: After import'),
      'group' => t('Integration'),
      'module' => 'integration',
      'variables' => [
        'consumer' => [
          'type' => 'integration_consumer',
          'label' => t('Integration: Consumer'),
        ],
      ],
    ],
    'integration_consumer_pre_rollback' => [
      'label' => t('Consumer: Before rollback'),
      'group' => t('Integration'),
      'module' => 'integration',
      'variables' => [
        'consumer' => [
          'type' => 'integration_consumer',
          'label' => t('Integration: Consumer'),
        ],
      ],
    ],
    'integration_consumer_post_rollback' => [
      'label' => t('Consumer: After rollback'),
      'group' => t('Integration'),
      'module' => 'integration',
      'variables' => [
        'consumer' => [
          'type' => 'integration_consumer',
          'label' => t('Integration: Consumer'),
        ],
      ],
    ],
  ];
}

/**
 * Implementation of hook_rules_action_info().
 */
function integration_consumer_rules_action_info() {
  return [
    'integration_consumer_rules_import_content_from_backend' => [
      'label' => t('Consumer: Import content from backend'),
      'group' => t('Integration'),
      'parameter' => [
        'consumer' => [
          'type' => 'token',
          'label' => t('Consumer'),
          'options list' => 'integration_consumer_rules_get_consumer_list',
          'optional' => FALSE,
        ],
      ],
      'module' => 'integration_consumer',
    ],
  ];
}

/**
 * Rules action callback: import content from given consumer.
 *
 * @param string $consumer_name
 *    Consumer machine name.
 */
function integration_consumer_rules_import_content_from_backend($consumer_name) {
  try {
    $consumer = \Drupal\integration_consumer\ConsumerFactory::getInstance($consumer_name);
    $consumer->fetchAll();
  }
  catch (\Drupal\integration\Exceptions\BaseException $e) {
    rules_log($e->getMessage(), [], RulesLog::ERROR);
    watchdog_exception("integration", $e);
  }
}

/**
 * Return consumer list.
 *
 * @return array
 *    List of suitable producer plugins.
 */
function integration_consumer_rules_get_consumer_list() {
  $options = [];
  foreach (entity_load('integration_consumer') as $producer) {
    /** @var \Drupal\integration\Configuration\AbstractConfiguration $producer */
    $options[$producer->getMachineName()] = $producer->getName();
  }
  return $options;
}

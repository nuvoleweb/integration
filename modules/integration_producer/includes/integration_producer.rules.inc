<?php

/**
 * @file
 * Contains Rules-related hook implementations.
 */

/**
 * Implementation of hook_rules_action_info().
 */
function integration_producer_rules_action_info() {
  return [
    'integration_producer_rules_action_build_document' => [
      'label' => t('Producer: Build document form a node'),
      'group' => t('Integration'),
      'parameter' => [
        'node' => [
          'type' => 'node',
          'label' => t('Content'),
        ],
        'producer' => [
          'type' => 'token',
          'label' => t('Producer'),
          'options list' => 'integration_producer_rules_get_node_producer_list',
          'optional' => FALSE,
        ],
      ],
      'provides' => [
        'document' => [
          'label' => t('Document'),
          'type' => 'integration_document',
        ],
        'resource_schema' => [
          'label' => t('Resource schema'),
          'type' => 'integration_resource_schema',
        ],
      ],
      'module' => 'integration_producer',
    ],
  ];
}

/**
 * Rules action callback: build a document given a node.
 *
 * @param object $node
 *    Node object.
 * @param string $producer_name
 *    Producer machine name.
 *
 * @return array
 *    Rules variable consisting of the built Document object.
 */
function integration_producer_rules_action_build_document($node, $producer_name) {
  $return = ['document' => NULL, 'resource_schema' => NULL];
  try {
    // @todo: Validate that current bundle is actually supported by the producer.
    $producer = \Drupal\integration_producer\ProducerFactory::getInstance($producer_name);
    $return = [
      'document' => $producer->build($node),
      'resource_schema' => $producer->getConfiguration()->getResourceSchema(),
    ];
  }
  catch (\Drupal\integration\Exceptions\BaseException $e) {
    rules_log($e->getMessage(), [], RulesLog::ERROR);
    watchdog_exception("integration", $e);
  }
  return $return;
}

/**
 * Return producer list.
 *
 * @return array
 *    List of suitable producer plugins.
 */
function integration_producer_rules_get_node_producer_list() {
  $options = [];
  try {
    foreach (entity_load('integration_producer') as $producer) {
      /** @var \Drupal\integration\Configuration\AbstractConfiguration $producer */
      if ($producer->getPlugin() == 'node_producer') {
        $options[$producer->getMachineName()] = $producer->getName();
      }
    }
  }
  catch (\Drupal\integration\Exceptions\BaseException $e) {
    rules_log($e->getMessage(), [], RulesLog::ERROR);
    watchdog_exception("integration", $e);
  }
  return $options;
}

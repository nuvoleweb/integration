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
    'integration_producer_build_document' => [
      'label' => t('Build document form a node'),
      'group' => t('Integration Producer'),
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
        'integration_document' => [
          'label' => t('Document'),
          'type' => 'integration_document',
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
function integration_producer_build_document($node, $producer_name) {
  $producer = \Drupal\integration_producer\ProducerFactory::getInstance($producer_name);
  // @todo: Validate that current bundle is actually supported by the producer.
  return ['integration_document' => $producer->build($node)];
}

/**
 * Return producer lists.
 *
 * @return array
 *    List of suitable producer plugins.
 */
function integration_producer_rules_get_node_producer_list() {
  $options = [];
  foreach (entity_load('integration_producer') as $producer) {
    /** @var \Drupal\integration\Configuration\AbstractConfiguration $producer */
    if ($producer->getPlugin() == 'node_producer') {
      $options[$producer->getMachineName()] = $producer->getName();
    }
  }
  return $options;
}

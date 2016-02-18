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
  ];
}

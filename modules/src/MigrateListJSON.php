<?php

/**
 * @file
 * Contains \Drupal\integration_migrate\MigrateListJSON.
 */

namespace Drupal\integration_migrate;

// @codingStandardsIgnoreStart
/**
 * Class MigrateListJSON.
 *
 * @package Drupal\integration_migrate
 */
class MigrateListJSON extends \MigrateListJSON {

  /**
   * {@inheritdoc}
   */
  protected function getIDsFromJSON(array $data) {
    $return = [];
    foreach ($data['results'] as $item) {
      $return[] = $item['id'];
    }
    return $return;
  }

}
// @codingStandardsIgnoreEnd

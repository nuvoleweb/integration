<?php

/**
 * @file
 * Contains \Drupal\integration_consumer\Migrate\MigrateListJSON.
 */

namespace Drupal\integration_consumer\Migrate;


// @codingStandardsIgnoreStart

/**
 * Class MigrateListJSON.
 *
 * @package Drupal\integration_consumer\Migrate
 *
 * @todo: move this class under Backend namespace.
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

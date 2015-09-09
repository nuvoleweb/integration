<?php

/**
 * @file
 * Contains \Drupal\integration\Consumer\Migrate\MigrateListJSON.
 */

namespace Drupal\integration\Consumer\Migrate;

use Drupal\integration\Document\Document;

// @codingStandardsIgnoreStart

/**
 * Class MigrateListJSON.
 *
 * @package Drupal\integration\Consumer\Migrate
 *
 * @todo: move this class under Backend namespace.
 */
class MigrateListJSON extends \MigrateListJSON {

  /**
   * {@inheritdoc}
   */
  protected function getIDsFromJSON(array $data) {
    $return = array();
    foreach ($data['results'] as $item) {
      $return[] = $item['id'];
    }
    return $return;
  }

}
// @codingStandardsIgnoreEnd

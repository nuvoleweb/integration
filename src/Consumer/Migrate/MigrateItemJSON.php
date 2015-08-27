<?php

/**
 * @file
 * Contains \Drupal\integration\Consumer\Migrate\MigrateItemJSON.
 */

namespace Drupal\integration\Consumer\Migrate;

use Drupal\integration\Document\Document;

/**
 * Class MigrateItemJSON.
 *
 * @package Drupal\integration\Consumer\Migrate
 */
class MigrateItemJSON extends \MigrateItemJSON {

  /**
   * {@inheritdoc}
   */
  public function getItem($id) {
    $current_item = parent::getItem($id);
    $document = new Document($current_item);
    return new DocumentWrapper($document);
  }

}

<?php

/**
 * @file
 * Contains \Drupal\integration_migrate\MigrateItemJSON.
 */

namespace Drupal\integration_migrate;

use Drupal\integration\Document\Document;

/**
 * Class MigrateItemJSON.
 *
 * @package Drupal\integration_migrate
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

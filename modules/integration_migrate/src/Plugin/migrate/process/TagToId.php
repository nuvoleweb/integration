<?php

namespace Drupal\integration_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * This plugin generates entities within the process plugin.
 *
 * @MigrateProcessPlugin(
 *   id = "tag_to_id"
 * )
 *
 * @Todo: Normally we should use EntityLookup for this..
 */
class TagToId extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if ($terms = taxonomy_term_load_multiple_by_name($value)) {
      /** @var \Drupal\taxonomy\Entity\Term $term */
      $term = reset($terms);
      return $term->id();
    }
    return NULL;
  }

}

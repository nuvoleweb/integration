<?php

/**
 * @file
 * Contains TitleMappingHandler.
 */

namespace Drupal\integration_consumer\MappingHandler;

/**
 * Class TitleMappingHandler.
 *
 * @package Drupal\integration_consumer\MappingHandler
 */
class TitleMappingHandler extends AbstractMappingHandler {

  /**
   * {@inheritdoc}
   */
  public function process($destination_field, $source_field = NULL) {
    // Handle Title replacements.
    $source_field = !$source_field ? $destination_field : $source_field;

    $entity_type = $this->getConsumer()->getDestinationEntityType();
    $bundle = $this->getConsumer()->getConfiguration()->getEntityBundle();
    $field_replacement = title_field_replacement_get_label_field($entity_type, $bundle);
    $legacy_field = title_field_replacement_get_legacy_field($entity_type, $field_replacement['field_name']);

    if ($destination_field == $legacy_field && title_field_replacement_enabled($entity_type, $bundle, $legacy_field)) {
      $this->getConsumer()->addFieldMapping($field_replacement['field_name'], $source_field, FALSE);
    }
  }

}

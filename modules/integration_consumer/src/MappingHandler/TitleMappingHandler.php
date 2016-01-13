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
  public function process() {
    $entity_type = $this->getConsumer()->getDestinationEntityType();
    if (module_exists('title') && title_field_replacement_is_label($entity_type, $this->destinationField)) {
      $bundle = $this->getConsumer()->getConfiguration()->getEntityBundle();
      $field_replacement = title_field_replacement_get_label_field($entity_type, $bundle);
      $legacy_field = title_field_replacement_get_legacy_field($entity_type, $field_replacement['field_name']);
      $this->getConsumer()->addFieldMapping($legacy_field, $this->sourceField, FALSE);
    }
  }

}

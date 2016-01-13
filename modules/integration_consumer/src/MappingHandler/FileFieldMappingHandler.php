<?php

/**
 * @file
 * Contains FileFieldMappingHandler.
 */

namespace Drupal\integration_consumer\MappingHandler;

/**
 * Class FileFieldMappingHandler.
 *
 * @package Drupal\integration_consumer\MappingHandler
 */
class FileFieldMappingHandler extends AbstractMappingHandler {

  /**
   * {@inheritdoc}
   */
  public function process() {
    $field_info = field_info_field($this->destinationField);
    if (in_array($field_info['type'], ['image', 'file'])) {
      $this->getConsumer()->addFieldMapping("$this->destinationField:file_replace")->defaultValue(FILE_EXISTS_REPLACE);
    }
  }

}

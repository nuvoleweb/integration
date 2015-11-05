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
  public function process($destination_field, $source_field = NULL) {
    $field_info = field_info_field($destination_field);
    if (in_array($field_info['type'], ['image', 'file'])) {
      $this->getConsumer()->addFieldMapping("$destination_field:file_replace")->defaultValue(FILE_EXISTS_REPLACE);
    }
  }

}

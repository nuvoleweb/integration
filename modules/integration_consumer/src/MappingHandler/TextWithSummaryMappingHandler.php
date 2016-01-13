<?php

/**
 * @file
 * Contains TextWithSummaryMappingHandler.
 */

namespace Drupal\integration_consumer\MappingHandler;

/**
 * Class TextWithSummaryMappingHandler.
 *
 * @package Drupal\integration_consumer\MappingHandler
 */
class TextWithSummaryMappingHandler extends AbstractMappingHandler {

  /**
   * {@inheritdoc}
   */
  public function process() {
    $field_info = field_info_field($this->destinationField);
    if (in_array($field_info['type'], ['text_with_summary'])) {
      // @todo: Make this a configuration parameters.
      // Mapping handlers should expose setting forms.
      $this->getConsumer()->addFieldMapping("$this->destinationField:format")->defaultValue('full_html');
    }
  }

}

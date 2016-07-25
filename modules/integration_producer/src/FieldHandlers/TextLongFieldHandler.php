<?php

/**
 * @file
 * Contains TextLongFieldHandler.
 */

namespace Drupal\integration_producer\FieldHandlers;

/**
 * Class TextLongFieldHandler.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
class TextLongFieldHandler extends AbstractFieldHandler {

  /**
   * {@inheritdoc}
   */
  public function getSubFieldList() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function processField() {

    foreach ($this->getFieldValues() as $value) {
      // Only add the field value and remove the format.
      // @todo: This should be configurable.
      $this->getDocument()->addFieldValue($this->getDestinationField(), $value['value']);
    }
  }

}

<?php

/**
 * @file
 * Contains TextFieldHandler.
 */

namespace Drupal\integration_producer\FieldHandlers;

/**
 * Class TextFieldHandler.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
class TextFieldHandler extends AbstractFieldHandler {

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
      $this->getDocument()->addFieldValue($this->getDestinationField(), $value);
    }
  }

}

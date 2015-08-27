<?php

/**
 * @file
 * Contains TextFieldHandler.
 */

namespace Drupal\integration\Producer\FieldHandlers;

/**
 * Class TextFieldHandler.
 *
 * @package Drupal\integration\Producer\FieldHandlers
 */
class TextFieldHandler extends AbstractFieldHandler {

  /**
   * {@inheritdoc}
   */
  public function processField() {

    foreach ($this->getFieldValues() as $value) {
      $this->getDocument()->addFieldValue($this->fieldName, $value);
    }
  }

}

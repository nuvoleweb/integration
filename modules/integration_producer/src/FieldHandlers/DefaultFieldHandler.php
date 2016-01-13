<?php

/**
 * @file
 * Contains DefaultFieldHandler.
 */

namespace Drupal\integration_producer\FieldHandlers;

/**
 * Class DefaultFieldHandler.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
class DefaultFieldHandler extends AbstractFieldHandler {

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
      if (is_array($value)) {
        foreach ($value as $column_name => $column_value) {
          $this->getDocument()->addFieldValue($this->getDestinationField() . '_' . $column_name, $column_value);
        }
      }
      else {
        $this->getDocument()->addFieldValue($this->getDestinationField(), $value);
      }
    }
  }

}

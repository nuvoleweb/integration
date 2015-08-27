<?php

/**
 * @file
 * Contains TextWithSummaryFieldHandler.
 */

namespace Drupal\integration\Producer\FieldHandlers;

/**
 * Class TextWithSummaryFieldHandler.
 *
 * @package Drupal\integration\Producer\FieldHandlers
 */
class TextWithSummaryFieldHandler extends AbstractFieldHandler {

  /**
   * {@inheritdoc}
   */
  public function processField() {

    foreach ($this->getFieldValues() as $value) {
      $this->getDocument()->addFieldValue($this->fieldName, $value['value']);
      $this->getDocument()->addFieldValue($this->fieldName . '_summary', $value['summary']);
    }
  }

}

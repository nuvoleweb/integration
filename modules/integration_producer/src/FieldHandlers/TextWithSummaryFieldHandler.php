<?php

/**
 * @file
 * Contains TextWithSummaryFieldHandler.
 */

namespace Drupal\integration_producer\FieldHandlers;

/**
 * Class TextWithSummaryFieldHandler.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
class TextWithSummaryFieldHandler extends AbstractFieldHandler {

  /**
   * {@inheritdoc}
   */
  public function getSubFieldList() {
    return [
      'summary' => t('Summary text'),
    ];
  }

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

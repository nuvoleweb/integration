<?php

/**
 * @file
 * Contains DateFieldHandler.
 */

namespace Drupal\integration\Producer\FieldHandlers;

/**
 * Class DateFieldHandler.
 *
 * @package Drupal\integration\Producer\FieldHandlers
 */
class DateFieldHandler extends AbstractFieldHandler {

  /**
   * Default Entity Wrapper date format.
   */
  const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';

  /**
   * {@inheritdoc}
   */
  public function processField() {

    foreach ($this->getFieldValues() as $value) {

      // Set default values if none given.
      $value['value2'] = isset($value['value2']) ? $value['value'] : '';
      $value['timezone'] = isset($value['timezone']) ? $value['timezone'] : '';

      // Make sure we convert timestamp into default date format.
      if ($value['date_type'] == 'datestamp') {
        $value['value'] = date(self::DEFAULT_DATE_FORMAT, $value['value']);
        $value['value2'] = date(self::DEFAULT_DATE_FORMAT, $value['value2']);
      }

      // Set field values on document.
      $this->getDocument()->addFieldValue($this->fieldName . '_start', $value['value']);
      $this->getDocument()->addFieldValue($this->fieldName . '_end', $value['value2']);
      $this->getDocument()->addFieldValue($this->fieldName . '_timezone', $value['timezone']);
    }
  }

}

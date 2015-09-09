<?php

/**
 * @file
 * Contains ImageFieldHandler.
 */

namespace Drupal\integration_producer\FieldHandlers;

use Drupal\integration_producer\FieldHandlers\FileFieldHandler;

/**
 * Class ImageFieldHandler.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
class ImageFieldHandler extends FileFieldHandler {

  /**
   * {@inheritdoc}
   */
  public function processField() {
    parent::processField();

    foreach ($this->getFieldValues() as $value) {
      $this->getDocument()->addFieldValue($this->fieldName . '_alt', $value['alt']);
      $this->getDocument()->addFieldValue($this->fieldName . '_title', $value['title']);
    }
  }

}

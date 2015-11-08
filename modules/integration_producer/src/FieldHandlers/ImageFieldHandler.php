<?php

/**
 * @file
 * Contains ImageFieldHandler.
 */

namespace Drupal\integration_producer\FieldHandlers;

/**
 * Class ImageFieldHandler.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
class ImageFieldHandler extends FileFieldHandler {

  /**
   * {@inheritdoc}
   */
  public function getSubFieldList() {
    return parent::getSubFieldList() + [
      'alt' => t('Image alt text'),
      'title' => t('Image title'),
    ];
  }

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

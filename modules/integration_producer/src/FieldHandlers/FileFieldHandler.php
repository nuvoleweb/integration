<?php

/**
 * @file
 * Contains FileFieldHandler.
 */

namespace Drupal\integration_producer\FieldHandlers;

/**
 * Class FileFieldHandler.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
class FileFieldHandler extends AbstractFieldHandler {

  /**
   * {@inheritdoc}
   */
  public function getSubFieldList() {
    return [
      'path' => t('File path'),
      'size' => t('File size'),
      'mime' => t('File mime'),
      'status' => t('File status'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function processField() {

    foreach ($this->getFieldValues() as $value) {
      $value['uri'] = $value['uri'] ? file_create_url($value['uri']) : '';
      $this->getDocument()->addFieldValue($this->fieldName . '_path', $value['uri']);
      $this->getDocument()->addFieldValue($this->fieldName . '_size', $value['filesize']);
      $this->getDocument()->addFieldValue($this->fieldName . '_mime', $value['filemime']);
      $this->getDocument()->addFieldValue($this->fieldName . '_status', $value['status']);
    }
  }

}

<?php

/**
 * @file
 * Contains TaxonomyTermReferenceFieldHandler.
 */

namespace Drupal\integration_producer\FieldHandlers;

/**
 * Class TaxonomyTermReferenceFieldHandler.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
class TaxonomyTermReferenceFieldHandler extends AbstractFieldHandler {

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
    // @todo: This should consider translation and reference to another migration.
    foreach ($this->getFieldValues() as $term) {
      if (is_object($term) && !empty($term)) {
        $this->getDocument()->addFieldValue($this->getDestinationField(), $term->name);
      }
    }
  }

}

<?php

/**
 * @file
 * Contains FieldHandlers.
 */

namespace Drupal\integration_producer\FieldHandlers;

use Drupal\integration\Document\DocumentInterface;
use Drupal\integration_producer\EntityWrapper\EntityWrapper;

/**
 * Interface FieldHandlerInterface.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
interface FieldHandlerInterface {

  /**
   * Return field values for current language.
   *
   * Values will be normalized to array of values, even for single fields.
   * Field handlers should use this method in their
   * FieldHandlerInterface::processField() implementation in order to walk
   * through field values.
   *
   * @return array
   *    Normalized current field values.
   */
  public function getFieldValues();

  /**
   * List of sub-fields the current field handler will be providing.
   *
   * Sub-fields are usually derived by the field schema columns, although they
   * might be defined by the field handler itself and produced in its
   * FieldHandlerInterface::processField() implementation.
   *
   * Sub-fields definition is used on user interface too, so you want to use
   * the t() function for their labels.
   *
   * This method must be implemented by field handlers classes.
   *
   * @return array
   *    Array of sub-fields labels, keyed by their machine names, if any.
   */
  public function getSubFieldList();

  /**
   * Entity wrapper the producer has been instantiated with.
   *
   * @return EntityWrapper
   *    Entity wrapper object.
   */
  public function getEntityWrapper();

  /**
   * Get document handler the producer has been instantiated with.
   *
   * @return DocumentInterface
   *    Document object.
   */
  public function getDocument();

  /**
   * Process and assign current field to target document object.
   *
   * This method must be implemented by field handlers classes.
   */
  public function processField();

  /**
   * Process current field.
   */
  public function process();

}

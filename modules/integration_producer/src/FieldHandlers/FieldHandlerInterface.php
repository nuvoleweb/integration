<?php

/**
 * @file
 * Contains FieldHandlers.
 */

namespace Drupal\integration\Producer\FieldHandlers;

use Drupal\integration\Document\DocumentInterface;
use Drupal\integration\Producer\EntityWrapper\EntityWrapper;
use Drupal\integration\Document\Document;

/**
 * Interface FieldHandlerInterface.
 *
 * @package Drupal\integration\Producer\FieldHandlers
 */
interface FieldHandlerInterface {

  /**
   * Return field values for current language.
   *
   * Values will be normalized to array of values, even for single fields.
   *
   * @return array
   *    Current, normalized field values.
   */
  public function getFieldValues();

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
   * Process and assign current field to document.
   */
  public function processField();

  /**
   * Process current field.
   */
  public function process();

}

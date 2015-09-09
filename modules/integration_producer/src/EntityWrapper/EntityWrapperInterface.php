<?php

/**
 * @file
 * Contains EntityWrapper.
 */

namespace Drupal\integration_producer\EntityWrapper;

/**
 * Interface EntityWrapperInterface.
 *
 * @package Drupal\integration_producer\EntityWrapper
 */
interface EntityWrapperInterface {

  /**
   * Return list of all entity's properties.
   *
   * @return array[string]
   *    Array of property names.
   */
  public function getPropertyList();

  /**
   * Return list of all entity's fields.
   *
   * @return array[string]
   *    Array of field names.
   */
  public function getFieldList();

  /**
   * Check weather $name is a field or not.
   *
   * @param string $name
   *    Field name.
   *
   * @return bool
   *    TRUE if property, FALSE otherwise.
   */
  public function isField($name);

  /**
   * Get field value, given a certain language.
   *
   * @param string $name
   *    Field name.
   *
   * @return array
   *    Field values in specified language.
   */
  public function getField($name, $language = NULL);

  /**
   * Check weather it is a property or not.
   *
   * @param string $name
   *    Property name.
   *
   * @return bool
   *    TRUE if property, FALSE otherwise.
   */
  public function isProperty($name);

  /**
   * Get property value.
   *
   * @param string $name
   *    Property name.
   *
   * @return string
   *    Property value.
   */
  public function getProperty($name);

  /**
   * Get available languages for current entity.
   *
   * @return array
   *    Array of language codes.
   */
  public function getAvailableLanguages();

  /**
   * Get default language for current entity.
   *
   * @return string
   *    Default language code.
   */
  public function getDefaultLanguage();

}

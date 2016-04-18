<?php

/**
 * @file
 * Contains Drupal\integration\Document\DocumentInterface.
 */

namespace Drupal\integration\Document;

/**
 * Interface DocumentInterface.
 *
 * @package Drupal\integration\Document
 */
interface DocumentInterface {

  /**
   * Get document internal id.
   *
   * @return string
   *    Returns document ID.
   */
  public function getId();

  /**
   * Return list of document's field machine names.
   *
   * @return array
   *    List of field machine names.
   */
  public function getFieldMachineNames();

  /**
   * Return actual list of document's field.
   *
   * @return array
   *    List of fields.
   */
  public function getFields();

  /**
   * Get list of field values for current language.
   *
   * @return array
   *    List of field values in current language.
   */
  public function getCurrentLanguageFieldsValues();

  /**
   * Get document default language.
   *
   * @return string
   *    Document's default language.
   */
  public function getDefaultLanguage();

  /**
   * Get list document's available languages.
   *
   * @return array
   *    Document's available languages.
   */
  public function getAvailableLanguages();

  /**
   * Get value of a specific field.
   *
   * @param string $field_name
   *    Document field name.
   * @param string|NULL $language
   *    Get field value in a specific language. Uses current language if NULL.
   *
   * @return mixed
   *    Field values in current language.
   *    If single-value field then it returns the only available value.
   *    If multi-value field then it returns the actual array of values.
   */
  public function getFieldValue($field_name, $language = NULL);

  /**
   * Set current language for the document.
   *
   * @param string $language
   *    Language code in ISO 639-1 format.
   *
   * @return DocumentInterface
   *    Set current language and return document object.
   */
  public function setCurrentLanguage($language = NULL);

  /**
   * Set metadata name/value pair on the document object.
   *
   * @param string $name
   *    Metadata name.
   * @param string $value
   *    Metadata value.
   *
   * @return DocumentInterface
   *    Set metadata property and return document object.
   */
  public function setMetadata($name, $value);

  /**
   * Set field name/value pair on the document object.
   *
   * @param string $name
   *    Field name.
   * @param mixed $value
   *    Field value, either a string or an array.
   *
   * @return DocumentInterface
   *    Set field value and return document object.
   */
  public function setField($name, $value);

  /**
   * Add a value to a multiple field.
   *
   * @param string $name
   *    Field name.
   * @param string $value
   *    Field value.
   *
   * @return DocumentInterface
   *    Set field value and return document object.
   */
  public function addFieldValue($name, $value);

  /**
   * Get metadata value.
   *
   * @param string $name
   *    Metadata name.
   *
   * @return string|null
   *    Metadata value.
   */
  public function getMetadata($name);

  /**
   * Get raw document object.
   *
   * @return mixed
   *    Raw document object.
   */
  public function getDocument();

  /**
   * Get document's current language.
   *
   * @return string
   *    Current language code in ISO 639-1 format.
   */
  public function getCurrentLanguage();

  /**
   * Delete metadata.
   *
   * @param string $name
   *    Metadata name.
   *
   * @return DocumentInterface
   *    Delete metadata and return document object.
   */
  public function deleteMetadata($name);

}

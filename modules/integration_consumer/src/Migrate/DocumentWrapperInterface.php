<?php

/**
 * @file
 * Contains DocumentWrapperInterface.
 */

namespace Drupal\integration_consumer\Migrate;

/**
 * Interface DocumentWrapperInterface.
 *
 * @package Drupal\integration_consumer\Migrate
 */
interface DocumentWrapperInterface {

  /**
   * Get current document.
   *
   * Forwarded to Drupal\integration\Document\DocumentInterface.
   *
   * @return \Drupal\integration\Document\DocumentInterface
   *    Document object.
   */
  public function getDocument();

  /**
   * Get current document's available languages.
   *
   * Forwarded to Drupal\integration\Document\DocumentInterface.
   *
   * @return array
   *    Document's available languages.
   */
  public function getAvailableLanguages();

  /**
   * Get current document's default language.
   *
   * Forwarded to Drupal\integration\Document\DocumentInterface.
   *
   * @return string
   *    Document's default language.
   */
  public function getDefaultLanguage();

  /**
   * Set field values on current document for the specified language.
   *
   * Forwarded to Drupal\integration\Document\DocumentInterface.
   *
   * @param string $language
   *    Lang code in ISO 639-1 format, falls back to current language if NULL.
   */
  public function setSourceValues($language = NULL);

}

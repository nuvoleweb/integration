<?php

/**
 * @file
 * Contains \Drupal\integration_migrate\DocumentWrapper.
 */

namespace Drupal\integration_migrate;

use Drupal\integration\Document\DocumentInterface;

/**
 * Class DocumentWrapper.
 *
 * @package Drupal\integration_migrate
 *
 * Migrate enforces $source_row to be a stdClass object, so we need to wrap the
 * the document into a class that extends stdClass and exposes
 * all necessary methods to interact with MigrateAbstract::complete() callback.
 */
class DocumentWrapper extends \stdClass implements DocumentWrapperInterface {

  private $document = NULL;

  /**
   * {@inheritdoc}
   */
  public function __construct(DocumentInterface $document) {
    $this->setDocument($document);
    $this->setSourceValues();
  }

  /**
   * {@inheritdoc}
   */
  private function setDocument(DocumentInterface $document = NULL) {
    $this->document = $document;
  }

  /**
   * {@inheritdoc}
   */
  public function getDocument() {
    return $this->document;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableLanguages() {
    return $this->getDocument()->getAvailableLanguages();
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultLanguage() {
    return $this->getDocument()->getDefaultLanguage();
  }

  /**
   * {@inheritdoc}
   */
  public function setSourceValues($language = NULL) {
    $language = $language ? $language : $this->getDocument()->getDefaultLanguage();
    $this->getDocument()->setCurrentLanguage($language);
    $this->_id = $this->getDocument()->getId();
    $this->default_language = $language;
    foreach ($this->getDocument()->getCurrentLanguageFieldsValues() as $field_name => $value) {
      $this->{$field_name} = $value;
    }
  }

}

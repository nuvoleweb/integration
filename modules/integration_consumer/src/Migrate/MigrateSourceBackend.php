<?php

/**
 * @file
 * Contains MigrateSourceBackend.
 */

namespace Drupal\integration\Consumer\Migrate;

use Drupal\integration\Backend\AbstractBackend;

/**
 * Class MigrateSourceBackend.
 *
 * @package Drupal\integration\Consumer\Migrate
 */
class MigrateSourceBackend extends \MigrateSource {

  /**
   * Current backend.
   *
   * @var AbstractBackend
   */
  protected $backend;

  /**
   * List of documents IDs retrieved by current backend.
   *
   * @var array
   */
  protected $documentList = array();

  /**
   * Index in $documentList array of current document being processed.
   *
   * @var int
   */
  protected $currentId = 0;

  /**
   * Constructor.
   *
   * @param AbstractBackend $backend
   *    Backend instance.
   * @param array $options
   *    Migrate source options.
   */
  public function __construct(AbstractBackend $backend, array $options = array()) {
    parent::__construct($options);
    $this->backend = $backend;
  }

  /**
   * Return a string representing the source, for display in the UI.
   *
   * @return null|string
   *    String representing the source
   */
  public function __toString() {
    return t('Migrate source using %backend integration backend.', array('%backend' => $this->backend->getConfiguration()->getName()));
  }

  /**
   * Returns available fields to be mapped from the source, keyed by field name.
   *
   * @return array
   *    List of fields keyed by field name.
   */
  public function fields() {
    return array(
      '_id' => t('Backend content ID'),
    );
  }

  /**
   * Return the number of available source records.
   *
   * @return int
   *    Number of available records.
   */
  public function computeCount() {
    return count($this->documentList);
  }

  /**
   * Reset current document ID so to start a fresh traversal of the source data.
   */
  public function performRewind() {
    if (!$this->documentList) {
      $this->documentList = $this->backend->getDocumentList();
    }
    $this->currentId = 0;
  }

  /**
   * Fetch the next row of data, returning it as an Document object.
   *
   * @return DocumentWrapper|FALSE
   *    New document instance or FALSE if not more content is available.
   */
  public function getNextRow() {

    if ($this->currentId < $this->computeCount()) {
      $document = $this->backend->read($this->documentList[$this->currentId]);
      $document_wrapper = new DocumentWrapper($document);
      $this->currentId++;
      return $document_wrapper;
    }
    else {
      return FALSE;
    }
  }

}

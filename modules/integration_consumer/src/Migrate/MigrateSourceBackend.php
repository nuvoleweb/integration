<?php

/**
 * @file
 * Contains MigrateSourceBackend.
 */

namespace Drupal\integration_consumer\Migrate;

use Drupal\integration\Backend\AbstractBackend;

/**
 * Class MigrateSourceBackend.
 *
 * @package Drupal\integration_consumer\Migrate
 */
class MigrateSourceBackend extends \MigrateSource {

  /**
   * Current backend.
   *
   * @var AbstractBackend
   */
  protected $backend;

  /**
   * Machine name of a resource schema configuration object.
   *
   * @var string
   */
  protected $resource;

  /**
   * List of documents IDs retrieved by current backend.
   *
   * @var array
   */
  protected $documentList = [];

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
   * @param string $resource_schema
   *    Machine name of a resource schema configuration object.
   * @param array $options
   *    Migrate source options.
   */
  public function __construct(AbstractBackend $backend, $resource_schema, array $options = []) {
    parent::__construct($options);
    $this->backend = $backend;
    $this->resource = $resource_schema;
  }

  /**
   * Return a string representing the source, for display in the UI.
   *
   * @return null|string
   *    String representing the source
   */
  public function __toString() {
    return t('Migrate source using %backend integration backend.', ['%backend' => $this->backend->getConfiguration()->getName()]);
  }

  /**
   * Returns available fields to be mapped from the source, keyed by field name.
   *
   * @return array
   *    List of fields keyed by field name.
   */
  public function fields() {
    return [
      '_id' => t('Backend content ID'),
    ];
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
      $this->documentList = $this->backend->listDocuments($this->resource);
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
      $document = $this->backend->read($this->resource, $this->documentList[$this->currentId]);
      $document_wrapper = new DocumentWrapper($document);
      $this->currentId++;
      return $document_wrapper;
    }
    else {
      return FALSE;
    }
  }

}

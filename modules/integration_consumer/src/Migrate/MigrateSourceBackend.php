<?php

/**
 * @file
 * Contains MigrateSourceBackend.
 */

namespace Drupal\integration_consumer\Migrate;

use Drupal\integration\Backend\AbstractBackend;
use Drupal\integration\Backend\BackendFactory;
use Drupal\integration_consumer\AbstractConsumer;
use Drupal\integration_migrate\DocumentWrapper;

/**
 * Class MigrateSourceBackend.
 *
 * @package Drupal\integration_consumer
 */
class MigrateSourceBackend extends \MigrateSource {

  /**
   * Current consumer.
   *
   * @var AbstractConsumer
   */
  protected $consumer;

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
  protected $documents = [];

  /**
   * Index in $documentList array of current document being processed.
   *
   * @var int
   */
  protected $currentId = 0;

  /**
   * Constructor.
   *
   * @param \Drupal\integration_consumer\AbstractConsumer $consumer
   *    Current consumer object instance.
   * @param array $options
   *    Migrate source options.
   */
  public function __construct(AbstractConsumer $consumer, array $options = []) {
    parent::__construct($options);
    $this->consumer = $consumer;
    $this->resource = $this->consumer->getConfiguration()->resource;
    $this->backend = BackendFactory::getInstance($this->consumer->getConfiguration()->getBackend());
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
    return count($this->documents);
  }

  /**
   * Reset current document ID so to start a fresh traversal of the source data.
   */
  public function performRewind() {
    if (!$this->documents) {
      $this->documents = $this->backend->find($this->resource, []);
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
      $document = $this->backend->read($this->resource, $this->documents[$this->currentId]);
      $document_wrapper = new DocumentWrapper($document);
      $this->currentId++;
      return $document_wrapper;
    }
    else {
      return FALSE;
    }
  }

}

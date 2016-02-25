<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\MemoryBackend.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\Document\Document;
use Drupal\integration\Document\DocumentInterface;

/**
 * Class MemoryBackend.
 *
 * @package Drupal\integration\Backend
 */
class MemoryBackend extends AbstractBackend {

  /**
   * Backend data storage.
   *
   * @var array
   */
  private $storage = [];

  /**
   * {@inheritdoc}
   */
  public function find($resource_schema, $args = []) {
    $this->validateResourceSchema($resource_schema);

    // Search by remote document id.
    if (isset($args['id']) && isset($this->storage[$args['id']])) {
      return [$args['id']];
    }
    return array_keys($this->storage);
  }

  /**
   * {@inheritdoc}
   */
  public function create($resource_schema, DocumentInterface $document) {
    $this->validateResourceSchema($resource_schema);

    $document->setMetadata('_id', $this->getBackendContentId($document));
    $this->storage[$document->getId()] = $document->getDocument();
    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function read($resource_schema, $id) {
    $this->validateResourceSchema($resource_schema);

    if (isset($this->storage[$id])) {
      return new Document($this->storage[$id]);
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function update($resource_schema, DocumentInterface $document) {
    $this->validateResourceSchema($resource_schema);

    $this->storage[$document->getId()] = $document->getDocument();
    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function delete($resource_schema, $id) {
    $this->validateResourceSchema($resource_schema);

    unset($this->storage[$id]);
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getBackendContentId(DocumentInterface $document) {
    return $document->getMetadata('producer_content_id');
  }

}

<?php

/**
 * @file
 * Contains Drupal\integration_producer\ProducerInterface.
 */

namespace Drupal\integration_producer;

use Drupal\integration\Backend\AbstractBackend;
use Drupal\integration\Backend\BackendInterface;
use Drupal\integration\Document\DocumentInterface;
use Drupal\integration_producer\EntityWrapper\EntityWrapper;

/**
 * Interface ProducerInterface.
 *
 * @package Drupal\integration_producer
 */
interface ProducerInterface {

  /**
   * Return entity type produced by the current plugin.
   *
   * @return string
   *    Entity type machine name.
   */
  public function getEntityType();

  /**
   * Return document type, derived from the current entity.
   *
   * @return string
   *    Type metadata value.
   */
  public function getDocumentType();

  /**
   * Return document creation date, derived from the current entity.
   *
   * @return int
   *    Creation date metadata value, as UNIX timestamp.
   */
  public function getDocumentCreationDate();

  /**
   * Return document creation date, derived from the current entity.
   *
   * @return int
   *    Creation date metadata value, as UNIX timestamp.
   */
  public function getDocumentUpdateDate();

  /**
   * Return current producer ID.
   *
   * @return string
   *    Producer identification string.
   */
  public function getProducerId();

  /**
   * Return content ID, unique to the producer.
   *
   * @return string
   *    Producer content identification string.
   */
  public function getProducerContentId();

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
   * Set resource backend machine name.
   *
   * @param string $backend
   *    Backend machine name.
   *
   * @return $this
   */
  public function setBackend($backend);

  /**
   * Get resource backend machine name.
   *
   * @return string
   */
  public function getBackend();

  /**
   * Get resource backend instance.
   *
   * @return BackendInterface
   *    Backend object instance.
   */
  public function getBackendInstance();

  /**
   * Build document object using the entity the producer was instantiated with.
   *
   * @param object $entity
   *    Raw Drupal entity object.
   *
   * @return DocumentInterface
   *    Built document object.
   */
  public function build($entity);

  /**
   * Push given entity object to producer's backend.
   *
   * @param object $entity
   *    Raw Drupal entity object.
   *
   * @return DocumentInterface
   *    Built document object.
   */
  public function push($entity);

}

<?php

/**
 * @file
 * Contains Drupal\integration\Producer\ProducerInterface.
 */

namespace Drupal\integration\Producer;

use Drupal\integration\Document\DocumentInterface;
use Drupal\integration\Producer\EntityWrapper\EntityWrapper;

/**
 * Interface ProducerInterface.
 *
 * @package Drupal\integration\Producer
 */
interface ProducerInterface {

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
   * Build document object using the entity the producer was instantiated with.
   *
   * @return DocumentInterface
   *    Built document object.
   */
  public function build();

}

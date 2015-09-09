<?php

/**
 * @file
 * Contains \Drupal\integration\Producer\NodeProducer.
 */

namespace Drupal\integration\Producer;

/**
 * Class NodeProducer.
 *
 * @package Drupal\integration\Producer
 */
class NodeProducer extends AbstractProducer {

  /**
   * {@inheritdoc}
   */
  public function getProducerContentId() {
    $wrapper = $this->getEntityWrapper();
    return 'node-' . str_replace('_', '-', $wrapper->getBundle()) . '-' . $wrapper->getIdentifier();
  }

  /**
   * {@inheritdoc}
   */
  public function getDocumentType() {
    return $this->getEntityWrapper()->getProperty('type');
  }

  /**
   * {@inheritdoc}
   */
  public function getDocumentCreationDate() {
    return $this->getEntityWrapper()->getProperty('created');
  }

  /**
   * {@inheritdoc}
   */
  public function getDocumentUpdateDate() {
    return $this->getEntityWrapper()->getProperty('changed');
  }

}

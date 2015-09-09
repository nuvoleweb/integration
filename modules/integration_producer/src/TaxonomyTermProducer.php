<?php

/**
 * @file
 * Contains \Drupal\integration_producer\TaxonomyTermProducer.
 */

namespace Drupal\integration_producer;

/**
 * Class TaxonomyTermProducer.
 *
 * @package Drupal\integration_producer
 */
class TaxonomyTermProducer extends AbstractProducer {

  /**
   * {@inheritdoc}
   */
  public function getProducerContentId() {
    $wrapper = $this->getEntityWrapper();
    return 'taxonomy-term-' . str_replace('_', '-', $wrapper->getBundle()) . '-' . $wrapper->getIdentifier();
  }

  /**
   * {@inheritdoc}
   */
  public function getDocumentType() {
    return $this->getEntityWrapper()->getProperty('vocabulary_machine_name');
  }

  /**
   * {@inheritdoc}
   */
  public function getDocumentCreationDate() {
    // @todo: return a value that actually makes sense for taxonomy terms.
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getDocumentUpdateDate() {
    // @todo: return a value that actually makes sense for taxonomy terms.
    return NULL;
  }

}

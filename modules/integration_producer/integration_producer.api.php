<?php

/**
 * @file
 * Contains API documentation.
 */

/**
 * Alter document being produced.
 *
 * @param \Drupal\integration_producer\ProducerInterface $producer
 *    Producer object.
 * @param \Drupal\integration\Document\DocumentInterface $document
 *    Document object.
 *
 * @see \Drupal\integration_producer\AbstractProducer::build()
 */
function hook_integration_producer_document_build_alter($producer, $document) {

}

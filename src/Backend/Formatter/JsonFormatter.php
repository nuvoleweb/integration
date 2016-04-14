<?php
/**
 * @file
 * Contains \Drupal\integration\Backend\JsonFormatter.
 */

namespace Drupal\integration\Backend\Formatter;

use Drupal\integration\Document\DocumentInterface;

/**
 * Class JsonFormatter.
 *
 * @package Drupal\integration\Backend\Formatter
 */
class JsonFormatter implements FormatterInterface {

  /**
   * {@inheritdoc}
   */
  public function encode(DocumentInterface $document) {
    return json_encode($document->getDocument(), JSON_PRETTY_PRINT);
  }

  /**
   * {@inheritdoc}
   */
  public function decode($raw) {
    return json_decode($raw);
  }

  /**
   * {@inheritdoc}
   */
  public function getExtension() {
    return 'json';
  }

  /**
   * {@inheritdoc}
   */
  public function getContentType() {
    return 'application/json';
  }

}

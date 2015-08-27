<?php
/**
 * @file
 * Contains \Drupal\integration\Backend\Formatter.
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
  public function format(DocumentInterface $document) {
    return json_encode($document->getDocument(), JSON_PRETTY_PRINT);
  }

}

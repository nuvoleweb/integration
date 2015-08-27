<?php
/**
 * @file
 * Contains \Drupal\integration\Backend\Formatter.
 */

namespace Drupal\integration\Backend\Formatter;

use Drupal\integration\Document\DocumentInterface;

/**
 * Interface FormatterInterface.
 *
 * @package Drupal\integration\Backend\Formatter
 */
interface FormatterInterface {

  /**
   * Format and return a Document object in textual output.
   *
   * @param DocumentInterface $document
   *    Document handler object.
   *
   * @return string
   *    Textual representation of the Document object.
   */
  public function format(DocumentInterface $document);

}

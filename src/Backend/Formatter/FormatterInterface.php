<?php
/**
 * @file
 * Contains \Drupal\integration\Backend\FormatterInterface.
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
  public function encode(DocumentInterface $document);

  /**
   * Decode a string into an object.
   *
   * @param string $raw
   *    Raw text.
   *
   * @return \stdClass
   *    Decoded object.
   */
  public function decode($raw);

  /**
   * Get file extension of formatted files.
   *
   * @return string
   *    File extension, like: "json", "xml", etc.
   */
  public function getExtension();

  /**
   * Get content-type of formatted files.
   *
   * @return string
   *    Content-type, like: "application/json", "application/xml", etc.
   */
  public function getContentType();

}

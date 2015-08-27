<?php

/**
 * @file
 * Contains Drupal\integration\Backend\BackendInterface.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\Document\DocumentInterface;

/**
 * Interface BackendInterface.
 *
 * @package Drupal\integration\Backend
 */
interface BackendInterface {

  /**
   * Get response object.
   *
   * @return Response\ResponseInterface
   *    Response object instance.
   */
  public function getResponseHandler();

  /**
   * Set response object.
   *
   * @param Response\ResponseInterface $response
   *    Response object instance.
   */
  public function setResponseHandler(Response\ResponseInterface $response);

  /**
   * Get formatter object.
   *
   * @return Formatter\FormatterInterface
   *    Formatter object instance.
   */
  public function getFormatterHandler();

  /**
   * Set formatter object.
   *
   * @param Formatter\FormatterInterface $formatter
   *    Formatter object instance.
   */
  public function setFormatter(Formatter\FormatterInterface $formatter);

  /**
   * Return list of document IDs.
   *
   * @param int $max
   *    Max number of items IDs to return.
   *
   * @return array
   *    List of document IDs
   */
  public function getDocumentList($max = 0);

  /**
   * Create a new document and populate its backend ID.
   *
   * @param DocumentInterface $document
   *    Document object.
   *
   * @return DocumentInterface
   *    Document object with backend ID.
   */
  public function create(DocumentInterface $document);

  /**
   * Get a document from the backend, given its backend ID.
   *
   * @param string $id
   *    Document backend ID.
   *
   * @return DocumentInterface|false
   *    Document fetched from backend or FALSE if not found.
   */
  public function read($id);

  /**
   * Update an existing document.
   *
   * @param DocumentInterface $document
   *    Document object.
   *
   * @return DocumentInterface
   *    Updated document object.
   */
  public function update(DocumentInterface $document);

  /**
   * Delete a document from the backend, given its backend ID.
   *
   * @param string $id
   *    Document backend ID.
   *
   * @return bool
   *    TRUE if deleted FALSE if not found.
   */
  public function delete($id);

  /**
   * Get backend content ID.
   *
   * @param DocumentInterface $document
   *    Document object.
   *
   * @return string
   *    Backend content ID.
   */
  public function getBackendContentId(DocumentInterface $document);

}

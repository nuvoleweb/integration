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
  public function setFormatterHandler(Formatter\FormatterInterface $formatter);

  /**
   * Return list of document IDs.
   *
   * @param string $resource_schema
   *    Machine name of a resource schema configuration object.
   * @param int $max
   *    Max number of items IDs to return.
   *
   * @return array
   *    List of document IDs
   */
  public function listDocuments($resource_schema, $max = 0);

  /**
   * Create a new document and populate its backend ID.
   *
   * @param string $resource_schema
   *    Machine name of a resource schema configuration object.
   * @param DocumentInterface $document
   *    Document object.
   *
   * @return DocumentInterface
   *    Document object with backend ID.
   */
  public function create($resource_schema, DocumentInterface $document);

  /**
   * Get a document from the backend, given its backend ID.
   *
   * @param string $resource_schema
   *    Machine name of a resource schema configuration object.
   * @param string $id
   *    Document backend ID.
   *
   * @return DocumentInterface
   *    Document object with backend ID.
   */
  public function read($resource_schema, $id);

  /**
   * Update an existing document.
   *
   * @param string $resource_schema
   *    Machine name of a resource schema configuration object.
   * @param DocumentInterface $document
   *    Document object.
   *
   * @return DocumentInterface
   *    Document object with backend ID.
   */
  public function update($resource_schema, DocumentInterface $document);

  /**
   * Delete a document from the backend, given its backend ID.
   *
   * @param string $resource_schema
   *    Machine name of a resource schema configuration object.
   * @param string $id
   *    Document backend ID.
   *
   * @return bool
   *    TRUE if deleted FALSE if not found.
   */
  public function delete($resource_schema, $id);

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

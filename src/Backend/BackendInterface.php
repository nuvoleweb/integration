<?php

/**
 * @file
 * Contains Drupal\integration\Backend\BackendInterface.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\Backend\Authentication\AuthenticationInterface;
use Drupal\integration\Document\DocumentInterface;

/**
 * Interface BackendInterface.
 *
 * @package Drupal\integration\Backend
 */
interface BackendInterface {

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
   * Set authentication object.
   *
   * @return AuthenticationInterface
   *    Authentication object instance.
   */
  public function getAuthenticationHandler();

  /**
   * Set authentication component.
   *
   * @param AuthenticationInterface $authentication
   *    Authentication component object.
   */
  public function setAuthenticationHandler(AuthenticationInterface $authentication);

  /**
   * Return list of document IDs.
   *
   * @param string $resource_schema
   *    Machine name of a resource schema configuration object.
   * @param array $args
   *    Backend query arguments.
   *
   * @return array List of document IDs
   *    List of document IDs
   */
  public function find($resource_schema, $args = []);

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

  /**
   * Make sure a given resource schema is valid.
   *
   * A valid resource schema must exists and be supported by the backend.
   *
   * @param string $machine_name
   *    Resource schema configuration.
   */
  public function validateResourceSchema($machine_name);

}

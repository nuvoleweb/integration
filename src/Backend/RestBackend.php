<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\RestBackend.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\Document\Document;
use Drupal\integration\Document\DocumentInterface;

/**
 * Class RestBackend.
 *
 * Simple REST backend using standard drupal_http_request(), without overrides.
 *
 * @package Drupal\integration\Backend
 */
class RestBackend extends AbstractBackend {

  /**
   * {@inheritdoc}
   */
  public function getDocumentList($max = 0) {
    // @todo implement document list retrieval.
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function create(DocumentInterface $document) {
    $options = array();
    $options['method'] = 'POST';
    $options['data'] = $this->getFormatterHandler()->format($document);
    $response = $this->httpRequest($this->getResourceUri(), $options);

    $this->getResponseHandler()->setResponse($response);
    if (!$this->getResponseHandler()->hasErrors()) {
      return new Document($this->getResponseHandler()->getData());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function read($id) {
    $options = array();
    $options['method'] = 'GET';
    $response = $this->httpRequest($this->getResourceUri() . '/' . $id, $options);

    $this->getResponseHandler()->setResponse($response);
    if (!$this->getResponseHandler()->hasErrors()) {
      return new Document($this->getResponseHandler()->getData());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function update(DocumentInterface $document) {
    $options = array();
    $options['method'] = 'PUT';
    $options['data'] = $this->getFormatterHandler()->format($document);
    $response = $this->httpRequest($this->getResourceUri() . '/' . $this->getBackendContentId($document), $options);

    $this->getResponseHandler()->setResponse($response);
    if (!$this->getResponseHandler()->hasErrors()) {
      return new Document($this->getResponseHandler()->getData());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function delete($id) {
    $options = array();
    $options['method'] = 'DELETE';
    $response = $this->httpRequest($this->getResourceUri() . '/' . $id, $options);

    $this->getResponseHandler()->setResponse($response);
    if (!$this->getResponseHandler()->hasErrors()) {
      return TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getBackendContentId(DocumentInterface $document) {
    $options = array();
    $options['method'] = 'GET';
    $producer = $document->getMetadata('producer');
    $producer_content_id = $document->getMetadata('producer_content_id');
    if ($producer && $producer_content_id) {
      $response = $this->httpRequest($this->getConfiguration()->getOption('base_path') . '/uuid/' . $producer . '/' . $producer_content_id, $options);

      $this->getResponseHandler()->setResponse($response);
      if (!$this->getResponseHandler()->hasErrors()) {
        $data = $this->getResponseHandler()->getData();
        return $data->rows[0]->id;
      }
    }
  }

  /**
   * Forwards HTTP requests to drupal_http_request().
   *
   * @param string $url
   *    A string containing a fully qualified URI.
   * @param array $options
   *    Array of options.
   *
   * @return object
   *    Response object, as returned by drupal_http_request().
   *
   * @see drupal_http_request()
   */
  protected function httpRequest($url, array $options = array()) {
    global $conf;
    // Make sure we use standard drupal_http_request(), without overrides.
    $conf['drupal_http_request_function'] = FALSE;
    return drupal_http_request($url, $options);
  }

  /**
   * Get full, single resource URI.
   *
   * @return string
   *    Single resource URI.
   */
  protected function getResourceUri() {
    return $this->getConfiguration()->getOption('base_path') . '/' . $this->getConfiguration()->getOption('endpoint');
  }

  /**
   * Get full resources list URI.
   *
   * @return string $list
   *    List URI.
   */
  protected function getListUri() {
    return $this->getConfiguration()->getOption('base_path') . '/' . $this->getConfiguration()->getOption('list');
  }

}

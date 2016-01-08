<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\RestBackend.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\Document\Document;
use Drupal\integration\Document\DocumentInterface;
use Drupal\integration\Backend\Configuration\BackendConfiguration;

/**
 * Class RestBackend.
 *
 * Simple REST backend using standard drupal_http_request(), without overrides.
 *
 * @method BackendConfiguration getConfiguration()
 *
 * @package Drupal\integration\Backend
 */
class RestBackend extends AbstractBackend {

  /**
   * {@inheritdoc}
   */
  public function listDocuments($resource_schema, $max = 0) {
    $options['method'] = 'GET';
    $response = $this->httpRequest($this->getChangeFeedUri($resource_schema), $options);

    $return = [];
    $this->getResponseHandler()->setResponse($response);
    if (!$this->getResponseHandler()->hasErrors()) {
      $data = $this->getResponseHandler()->getData();
      foreach ($data->results as $item) {
        if (!isset($item->deleted)) {
          $return[] = $item->id;
        }
      }
    }
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function create($resource_schema, DocumentInterface $document) {

    // If document already exists then update it.
    if ($id = $this->getBackendContentId($document)) {
      $document->setMetadata('_id', $id);
      $this->update($resource_schema, $document);
    }
    else {
      $options['method'] = 'POST';
      $options['data'] = $this->getFormatterHandler()->encode($document);
      $response = $this->httpRequest($this->getResourceUri($resource_schema), $options);

      $this->getResponseHandler()->setResponse($response);
      if (!$this->getResponseHandler()->hasErrors()) {
        return new Document($this->getResponseHandler()->getData());
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function read($resource_schema, $id) {
    $options['method'] = 'GET';
    $response = $this->httpRequest($this->getResourceUri($resource_schema) . '/' . $id, $options);

    $this->getResponseHandler()->setResponse($response);
    if (!$this->getResponseHandler()->hasErrors()) {
      return new Document($this->getResponseHandler()->getData());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function update($resource_schema, DocumentInterface $document) {
    $options['method'] = 'PUT';
    $options['data'] = $this->getFormatterHandler()->encode($document);
    $response = $this->httpRequest($this->getResourceUri($resource_schema) . '/' . $this->getBackendContentId($document), $options);

    $this->getResponseHandler()->setResponse($response);
    if (!$this->getResponseHandler()->hasErrors()) {
      return new Document($this->getResponseHandler()->getData());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function delete($resource_schema, $id) {
    $options['method'] = 'DELETE';
    $response = $this->httpRequest($this->getResourceUri($resource_schema) . '/' . $id, $options);

    $this->getResponseHandler()->setResponse($response);
    if (!$this->getResponseHandler()->hasErrors()) {
      return TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getBackendContentId(DocumentInterface $document) {
    $options['method'] = 'GET';
    $producer = $document->getMetadata('producer');
    $producer_content_id = $document->getMetadata('producer_content_id');
    if ($producer && $producer_content_id) {
      $parts[] = $this->getConfiguration()->getPluginSetting('backend.base_url');
      $parts[] = $this->getConfiguration()->getPluginSetting('backend.backend_id');
      $parts[] = $producer;
      $parts[] = $producer_content_id;
      $url = implode('/', $parts);
      $response = $this->httpRequest($url, $options);

      $this->getResponseHandler()->setResponse($response);
      if (!$this->getResponseHandler()->hasErrors()) {
        $data = $this->getResponseHandler()->getData();
        return $data->rows[0]->id;
      }
    }
    return NULL;
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
   */
  protected function httpRequest($url, array $options = []) {
    global $conf;
    $authentication = $this->getAuthenticationHandler();
    $authentication->setContext(['url' => $url, 'options' => $options]);
    $authentication->authenticate();
    $context = $authentication->getContext();

    // Make sure we use standard drupal_http_request(), without overrides.
    $conf['drupal_http_request_function'] = FALSE;
    return $this->doRequest($context['url'], $context['options']);
  }

  /**
   * Wrapper about Drupal core drupal_http_request() to ease unit-testing.
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
  protected function doRequest($url, $options) {
    return drupal_http_request($url, $options);
  }

  /**
   * Get full, single resource URI.
   *
   * @param string $resource_schema
   *    Machine name of a resource schema configuration object.
   *
   * @return string
   *    Single resource URI.
   */
  protected function getResourceUri($resource_schema) {
    $base_url = $this->getConfiguration()->getPluginSetting('backend.base_url');
    $endpoint = $this->getConfiguration()->getResourceEndpoint($resource_schema);
    return "$base_url/$endpoint";
  }

  /**
   * Get resource change feed URI.
   *
   * @param string $resource_schema
   *    Machine name of a resource schema configuration object.
   *
   * @return string
   *    Resource change feed URI.
   */
  protected function getChangeFeedUri($resource_schema) {
    $base_url = $this->getConfiguration()->getPluginSetting('backend.base_url');
    $endpoint = $this->getConfiguration()->getResourceChangeFeed($resource_schema);
    return "$base_url/$endpoint";
  }

}

<?php

/**
 * @file
 * Contains RawResponse.
 */

namespace Drupal\integration\Backend\Response;

/**
 * Class RawResponse.
 *
 * To be used together with MemoryBackend, always returns success.
 *
 * @package Drupal\integration\Backend\Response
 */
class RawResponse extends AbstractResponse {

  /**
   * {@inheritdoc}
   */
  public function hasErrors() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getErrorMessage() {
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getStatusCode() {
    return 200;
  }

  /**
   * {@inheritdoc}
   */
  public function getStatusMessage() {
    return 'OK';
  }

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this->getResponse();
  }

}

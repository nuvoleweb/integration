<?php

/**
 * @file
 * Contains AbstractResponse.
 */

namespace Drupal\integration\Backend\Response;

/**
 * Class AbstractResponse.
 *
 * @package Drupal\integration\Backend\Response
 */
abstract class AbstractResponse implements ResponseInterface {

  /**
   * Response object, text or array.
   *
   * @var mixed
   */
  private $response;

  /**
   * {@inheritdoc}
   */
  public function getResponse() {
    if (isset($this->response)) {
      return $this->response;
    }
    else {
      throw new \InvalidArgumentException(t('Response object not set on @class', ['@class' => ___CLASS__]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setResponse($response) {
    $this->response = $response;
  }

}

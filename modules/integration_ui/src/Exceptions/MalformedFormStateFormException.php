<?php

/**
 * @file
 * Contains \Drupal\integration_ui\Exceptions\MalformedFormStateFormException
 */

namespace Drupal\integration_ui\Exceptions;

/**
 * Class MalformedFormStateFormException.
 *
 * @package Drupal\integration_ui\Exceptions
 */
class MalformedFormStateFormException extends FormException {

  /**
   * MalformedFormStateFormException constructor.
   *
   * @param string $message
   *    Additional message.
   */
  public function __construct($message) {
    $message = t('Form state array is malformed, reason: !message.', ['!message' => $message]);
    parent::__construct($message);
  }

}

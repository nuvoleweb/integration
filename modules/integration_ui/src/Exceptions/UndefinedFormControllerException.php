<?php

/**
 * @file
 * Contains \Drupal\integration_ui\Exceptions\UndefinedFormControllerException
 */

namespace Drupal\integration_ui\Exceptions;

/**
 * Class UndefinedFormControllerException.
 *
 * @package Drupal\integration_ui\Exceptions
 */
class UndefinedFormControllerException extends FormException {

  /**
   * UndefinedFormControllerException constructor.
   *
   * @param string $name
   *    Plugin or component name.
   */
  public function __construct($name) {
    $message = t('Plugin "!name" does not have a form controller defined.', ['!name' => $name]);
    parent::__construct($message);
  }

}

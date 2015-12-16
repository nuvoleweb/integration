<?php

/**
 * @file
 * Contains \Drupal\integration_ui\Exceptions\UndefinedFormHandlerException.
 */

namespace Drupal\integration_ui\Exceptions;

/**
 * Class UndefinedFormHandlerException.
 *
 * @package Drupal\integration_ui\Exceptions
 */
class UndefinedFormHandlerException extends FormException {

  /**
   * UndefinedFormHandlerException constructor.
   *
   * @param string $type
   *    Either "plugin" or "component".
   * @param string $name
   *    Plugin or component name.
   */
  public function __construct($type, $name) {
    if ($type == 'plugin') {
      $message = t('Plugin component type "!name" does not have a form handler defined.', ['!name' => $name]);
    }
    else {
      $message = t('Plugin type "!name" does not have a form handler defined.', ['!name' => $name]);
    }
    parent::__construct($message);
  }

}

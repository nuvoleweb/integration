<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\Authentication\AuthenticationInterface.
 */

namespace Drupal\integration\Backend\Authentication;
use Drupal\integration\ConfigurablePluginInterface;

/**
 * Interface AuthenticationInterface.
 *
 * @package Drupal\integration\Backend\Authentication
 */
interface AuthenticationInterface extends ConfigurablePluginInterface {

  /**
   * Authenticates and provides an authentication result
   *
   * @return bool
   *    TRUE if authenticated, FALSE otherwise
   */
  public function authenticate();

}

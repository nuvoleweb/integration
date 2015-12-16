<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\Authentication\HttpAuthentication.
 */

namespace Drupal\integration\Backend\Authentication;

/**
 * Class HttpAuthentication.
 *
 * @package Drupal\integration\Backend\Authentication
 */
class HttpAuthentication implements AuthenticationInterface {

  /**
   * {@inheritdoc}
   */
  public function authenticate() {
    return TRUE;
  }

}

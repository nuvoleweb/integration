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
class HttpAuthentication extends AbstractAuthentication {

  /**
   * {@inheritdoc}
   */
  public function authenticate() {
    return TRUE;
  }

}

<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\Authentication\NoAuthentication.
 */

namespace Drupal\integration\Backend\Authentication;

/**
 * Class NoAuthentication.
 *
 * @package Drupal\integration\Backend\Authentication
 */
class NoAuthentication extends AbstractAuthentication {

  /**
   * {@inheritdoc}
   */
  public function authenticate() {
    return TRUE;
  }

}

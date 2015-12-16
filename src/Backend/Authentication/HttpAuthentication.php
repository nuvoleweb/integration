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
    $configuration = $this->getConfiguration();
    $username = $configuration->getComponentSetting('authentication_handler', 'username');
    $password = $configuration->getComponentSetting('authentication_handler', 'password');

    $context = $this->getContext();
    list($protocol, $uri) = explode('://', $context['url']);
    $context['url'] = "$protocol://$username:$password@$uri";
    $this->setContext($context);
    return TRUE;
  }

}

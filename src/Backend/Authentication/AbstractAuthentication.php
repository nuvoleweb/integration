<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\Authentication\AbstractAuthentication.
 */

namespace Drupal\integration\Backend\Authentication;

use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Class AbstractAuthentication.
 *
 * @package Drupal\integration\Backend\Authentication
 */
abstract class AbstractAuthentication implements AuthenticationInterface {

  /**
   * Configuration object.
   *
   * @var AbstractConfiguration
   */
  private $configuration;

  /**
   * Context data which will be passed to the authentication callback.
   *
   * @var array
   */
  private $context;

  /**
   * AbstractAuthentication constructor.
   *
   * @param \Drupal\integration\Configuration\AbstractConfiguration $configuration
   *    AbstractConfiguration Class instance.
   */
  public function __construct(AbstractConfiguration $configuration) {
    $this->configuration = $configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(AbstractConfiguration $configuration) {
    $this->configuration = $configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function getContext() {
    return $this->context;
  }

  /**
   * {@inheritdoc}
   */
  public function setContext(array $context) {
    $this->context = $context;
  }

}

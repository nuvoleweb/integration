<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\Configuration\BackendConfiguration.
 */

namespace Drupal\integration\Backend\Configuration;

use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Class BackendConfiguration.
 *
 * @package Drupal\integration\Backend
 */
class BackendConfiguration extends AbstractConfiguration {

  /**
   * Formatter handler machine name.
   *
   * @var string
   */
  public $formatter = '';

  /**
   * Authentication handler machine name.
   *
   * @var string
   */
  public $authentication = '';

  /**
   * Get formatter handler name.
   *
   * @return string
   *    Formatter handler name.
   */
  public function getFormatter() {
    return isset($this->formatter) ? $this->formatter : '';
  }

  /**
   * Set formatter handler name.
   *
   * @param string $formatter
   *    Formatter handler name.
   */
  public function setFormatter($formatter) {
    $this->formatter = $formatter;
  }

  /**
   * Get authentication handler name.
   *
   * @return string
   *    Authentication handler name.
   */
  public function getAuthentication() {
    return $this->authentication;
  }

  /**
   * Set authentication handler name.
   *
   * @param string $authentication
   *    Authentication handler name.
   */
  public function setAuthentication($authentication) {
    $this->authentication = $authentication;
  }

  /**
   * {@inheritdoc}
   */
  public function validate() {

    if (!$this->getFormatter()) {
      $this->errors['formatter_handler'] = t('Formatter handler cannot be left empty.');
    }
    if (!$this->getAuthentication()) {
      $this->errors['authentication_handler'] = t('Authentication handler cannot be left empty.');
    }
    if (!$this->getPluginSetting('resource_schema')) {
      $this->errors['resource_schema'] = t('At least one resource schema need to be associated with this backend.');
    }
    return empty($this->errors);
  }

}

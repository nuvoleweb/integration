<?php

/**
 * @file
 * Contains BackendConfiguration.
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
   * @see hook_integration_backend_formatter_handler_info()
   *
   * @var string
   */
  public $formatter = '';

  /**
   * Response handler machine name.
   *
   * @see integration_backend_response_handler_info()
   *
   * @var string
   */
  public $response = '';


  /**
   * Authentication handler machine name.
   *
   * @see integration_backend_authentication_handler_info()
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
   * Get response handler name.
   *
   * @return string
   *    Response handler name.
   */
  public function getResponse() {
    return $this->response;
  }

  /**
   * Set response handler name.
   *
   * @param string $response
   *    Response handler name.
   */
  public function setResponse($response) {
    $this->response = $response;
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

    if (!$this->getResponse()) {
      $this->errors['response_handler'] = t('Response handler cannot be left empty.');
    }
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

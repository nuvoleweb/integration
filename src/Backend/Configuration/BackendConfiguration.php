<?php

/**
 * @file
 * Contains BackendConfiguration.
 */

namespace Drupal\integration\Backend\Configuration;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\AbstractComponentConfiguration;
use Drupal\integration\Configuration\FormTrait;
use Drupal\integration\PluginManager;

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

}

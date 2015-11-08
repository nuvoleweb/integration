<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\AbstractBackend.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\ConfigurablePluginInterface;
use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Class AbstractBackend.
 *
 * @package Drupal\integration\Backend
 */
abstract class AbstractBackend implements BackendInterface, ConfigurablePluginInterface {

  /**
   * Configuration object.
   *
   * @var Configuration\BackendConfiguration
   */
  private $configuration;

  /**
   * Response handler object.
   *
   * @var Response\ResponseInterface
   */
  private $response;

  /**
   * Formatter object.
   *
   * @var Formatter\FormatterInterface
   */
  private $formatter;

  /**
   * Constructor.
   *
   * @param Configuration\BackendConfiguration $configuration
   *    Configuration object.
   * @param Response\ResponseInterface $response
   *    Response handler object.
   * @param Formatter\FormatterInterface $formatter
   *    Formatter object.
   */
  public function __construct(Configuration\BackendConfiguration $configuration, Response\ResponseInterface $response, Formatter\FormatterInterface $formatter) {
    $this->setConfiguration($configuration);
    $this->setResponseHandler($response);
    $this->setFormatterHandler($formatter);
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
  public function getResponseHandler() {
    return isset($this->response) ? $this->response : '';
  }

  /**
   * {@inheritdoc}
   */
  public function setResponseHandler(Response\ResponseInterface $response) {
    $this->response = $response;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormatterHandler() {
    return isset($this->formatter) ? $this->formatter : '';
  }

  /**
   * {@inheritdoc}
   */
  public function setFormatterHandler(Formatter\FormatterInterface $formatter) {
    $this->formatter = $formatter;
  }

}

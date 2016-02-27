<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\AbstractBackend.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\Backend\Authentication\AuthenticationInterface;
use Drupal\integration\ConfigurablePluginInterface;
use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Exceptions\BackendException;
use Drupal\integration\ResourceSchema\ResourceSchemaFactory;

/**
 * Class AbstractBackend.
 *
 * @package Drupal\integration\Backend
 */
abstract class AbstractBackend implements BackendInterface, ConfigurablePluginInterface {

  /**
   * Configuration component.
   *
   * @var Configuration\BackendConfiguration
   */
  private $configuration;

  /**
   * Formatter component.
   *
   * @var Formatter\FormatterInterface
   */
  private $formatter;

  /**
   * Authentication component.
   *
   * @var AuthenticationInterface
   */
  private $authentication;

  /**
   * Constructor.
   *
   * @param Configuration\BackendConfiguration $configuration
   *    Configuration object.
   * @param Formatter\FormatterInterface $formatter
   *    Formatter object.
   * @param AuthenticationInterface $authentication
   *    Authentication handler object.
   */
  public function __construct(Configuration\BackendConfiguration $configuration, Formatter\FormatterInterface $formatter, AuthenticationInterface $authentication) {
    $this->setConfiguration($configuration);
    $this->setFormatterHandler($formatter);
    $this->setAuthenticationHandler($authentication);
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
  public function getFormatterHandler() {
    return isset($this->formatter) ? $this->formatter : '';
  }

  /**
   * {@inheritdoc}
   */
  public function setFormatterHandler(Formatter\FormatterInterface $formatter) {
    $this->formatter = $formatter;
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthenticationHandler() {
    return $this->authentication;
  }

  /**
   * {@inheritdoc}
   */
  public function setAuthenticationHandler(AuthenticationInterface $authentication) {
    $this->authentication = $authentication;
  }

  /**
   * Set backend setting value given its name and value.
   *
   * @param string $name
   *    Plugin setting name.
   * @param mixed $value
   *    Plugin setting value.
   *
   * @return $this
   */
  public function setBackendSetting($name, $value) {
    $this->getConfiguration()->setPluginSetting("backend.$name", $value);
    return $this;
  }

  /**
   * Set resource schemas supported by this backend.
   *
   * @param string $resource_schema
   *    Resource schema machine name.
   *
   * @return $this
   */
  public function setResourceSchema($resource_schema) {
    $values = $this->getConfiguration()->getPluginSetting('resource_schemas');
    $values[] = $resource_schema;
    $this->getConfiguration()->setPluginSetting('resource_schemas', $values);
    return $this;
  }

  /**
   * Ser resource schema setting.
   *
   * @param string $resource_schema
   *    Resource schema machine name.
   * @param string $name
   *    Setting name.
   * @param string $value
   *    Setting name.
   *
   * @return $this
   */
  public function setResourceSchemaSetting($resource_schema, $name, $value) {
    $this->getConfiguration()->setPluginSetting("resource_schema.$resource_schema.$name", $value);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function validateResourceSchema($machine_name) {
    // Load configuration, throws and exception if not found.
    ResourceSchemaFactory::getInstance($machine_name);

    $values = $this->getConfiguration()->getPluginSetting('resource_schemas');
    if (!in_array($machine_name, $values)) {
      throw new BackendException(t('Resource schema "@machine_name" not supported by "@backend" backend.', [
        '@machine_name' => $machine_name,
        '@backend' => $this->getConfiguration()->getMachineName(),
      ]));
    }
  }

}

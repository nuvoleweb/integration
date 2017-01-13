<?php

/**
 * @file
 * Contains \Drupal\integration\ResourceSchema\AbstractResourceSchema.
 */

namespace Drupal\integration\ResourceSchema;

use Drupal\integration\ConfigurablePluginInterface;
use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Class AbstractResourceSchema.
 *
 * @package Drupal\integration\ResourceSchema
 */
abstract class AbstractResourceSchema implements ResourceSchemaInterface, ConfigurablePluginInterface {

  /**
   * Configuration component.
   *
   * @var Configuration\ResourceSchemaConfiguration
   */
  private $configuration;

  /**
   * Constructor.
   *
   * @param Configuration\ResourceSchemaConfiguration $configuration
   *    Configuration object.
   */
  public function __construct(Configuration\ResourceSchemaConfiguration $configuration) {
    $this->setConfiguration($configuration);
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
   * Chainable method to add a field to the resource schema configuration.
   *
   * @param string $machine_name
   *    Field machine name.
   * @param string $label
   *    Field label.
   *
   * @return $this
   */
  public function setField($machine_name, $label) {
    $this->getConfiguration()->setPluginSetting("fields.$machine_name", $label);
    return $this;
  }

}

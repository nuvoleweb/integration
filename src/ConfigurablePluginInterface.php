<?php

/**
 * @file
 * Contains \Drupal\integration\ConfigurablePluginInterface.
 */

namespace Drupal\integration;

use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Interface ConfigurablePluginInterface.
 *
 * Implemented by plugins or plugin components that need to store
 * configuration entities.
 *
 * @package Drupal\integration\Configuration
 */
interface ConfigurablePluginInterface {

  /**
   * Set current configuration entity object.
   */
  public function setConfiguration(AbstractConfiguration $configuration);

  /**
   * Get reference to current configuration entity object.
   *
   * @return AbstractConfiguration
   *    Configuration entity object.
   */
  public function getConfiguration();

}

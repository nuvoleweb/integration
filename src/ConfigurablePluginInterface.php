<?php

/**
 * @file
 * Contains ConfigurablePluginInterface.
 */

namespace Drupal\integration;

use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Interface ConfigurablePluginInterface.
 *
 * Implemented typically by plugins or plugin components classes that need to
 * store with configuration entities.
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

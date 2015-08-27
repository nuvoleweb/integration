<?php

/**
 * @file
 * Contains ConfigurableInterface.
 */

namespace Drupal\integration\Configuration;

/**
 * Interface ConfigurableInterface.
 *
 * @package Drupal\integration\Configuration
 */
interface ConfigurableInterface {

  /**
   * Get configuration human readable name.
   *
   * @return string
   *    Configuration name.
   */
  public function setConfiguration(AbstractConfiguration $configuration);

  /**
   * Get configuration human readable name.
   *
   * @return string
   *    Configuration name.
   */
  public function getConfiguration();

}

<?php

/**
 * @file
 * Contains ConfigurationFactory.
 */

namespace Drupal\integration\Configuration;


/**
 * Interface ConfigurationFactory.
 *
 * @package Drupal\integration\Configuration
 */
class ConfigurationFactory {

  /**
   * Load configuration.
   *
   * Simply wraps entity_load_single() so we can mock entity loading in tests.
   *
   * @param string $type
   *    Configuration entity type.
   * @param string $machine_name
   *    Configuration entity machine name.
   *
   * @return AbstractConfiguration
   *    Loaded configuration entity.
   */
  public static function load($type, $machine_name) {
    if ($configuration = entity_load_single($type, $machine_name)) {
      return $configuration;
    }
    else {
      $args = ['@machine_name' => $machine_name, '@type' => $type];
      throw new \InvalidArgumentException(t('Configuration entity "@machine_name" of type "@type" not found.', $args));
    }
  }

}

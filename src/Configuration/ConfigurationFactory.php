<?php

/**
 * @file
 * Contains \Drupal\integration\Configuration\ConfigurationFactory.
 */

namespace Drupal\integration\Configuration;
use Drupal\integration\Exceptions\ConfigurationException;

/**
 * Class ConfigurationFactory.
 *
 * @package Drupal\integration\Configuration
 */
class ConfigurationFactory {

  /**
   * Internal configuration cache.
   *
   * @var array
   */
  private static $storage = [];

  /**
   * Array of valid configuration entity type names.
   *
   * @var array
   */
  private static $entityTypes = [
    'integration_backend',
    'integration_consumer',
    'integration_producer',
    'integration_resource_schema',
  ];

  /**
   * Load configuration.
   *
   * Simply wraps entity_load_single() so we can mock entity loading in tests.
   *
   * @param string $type
   *    Configuration entity type.
   * @param string $machine_name
   *    Configuration entity machine name.
   * @param bool $reset
   *    Whether to reset the internal cache for the requested configuration.
   *
   * @return \Drupal\integration\Configuration\AbstractConfiguration
   *    Loaded configuration entity.
   *
   * @throws \Drupal\integration\Exceptions\ConfigurationException
   *    Throws ConfigurationException.
   */
  public static function load($type, $machine_name, $reset = FALSE) {
    $type = self::getEntityType($type);
    if (!$reset && isset(self::$storage[$type][$machine_name])) {
      return self::$storage[$type][$machine_name];
    }
    elseif ($entities = entity_load($type, [$machine_name], [], $reset)) {
      self::$storage[$type][$machine_name] = reset($entities);
      return self::$storage[$type][$machine_name];
    }
    else {
      $args = ['@machine_name' => $machine_name, '@type' => $type];
      throw new ConfigurationException(t('Configuration entity "@machine_name" of type "@type" not found.', $args));
    }
  }

  /**
   * Create configuration entity stub with sensitive defaults.
   *
   * @param string $type
   *    Configuration entity type.
   * @param string $machine_name
   *    Configuration entity machine name.
   * @param array $values
   *   An array of values to set, keyed by property name.
   *
   * @return AbstractConfiguration
   *    Configuration entity stub.
   */
  public static function create($type, $machine_name, $values = []) {
    $type = self::getEntityType($type);
    /** @var AbstractConfiguration $configuration */
    $configuration = entity_create(self::getEntityType($type), $values);
    $configuration->name = $machine_name;
    $configuration->machine_name = $machine_name;
    $configuration->enabled = TRUE;
    $configuration->status = ENTITY_CUSTOM;
    self::$storage[$type][$machine_name] = $configuration;
    return self::$storage[$type][$machine_name];
  }

  /**
   * Normalize entity type name on get so we can use shorter names.
   *
   * @param string $type
   *    Configuration entity type.
   *
   * @return string
   *    Get normalized entity type name.
   *
   * @throws \Drupal\integration\Exceptions\ConfigurationException
   *    Throws ConfigurationException.
   */
  protected static function getEntityType($type) {
    $type = strstr($type, 'integration_') === FALSE ? 'integration_' . $type : $type;
    if (!in_array($type, self::$entityTypes)) {
      throw new ConfigurationException(t("!type is not a valid configuration entity type", ['!type' => $type]));
    }
    return $type;
  }

}

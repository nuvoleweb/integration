<?php

/**
 * @file
 * Contains BackendFactory.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Plugins\PluginManager;

/**
 * Interface BackendFactory.
 *
 * @package Drupal\integration\Backend
 */
class BackendFactory {

  /**
   * Keeps instances of already build backend objects.
   *
   * @var array[AbstractBackend]
   */
  static private $instances = [];

  /**
   * Instantiate and return a backend object given its configuration.
   *
   * @param string $machine_name
   *    Backend configuration machine name.
   * @param bool|FALSE $reset
   *    Whereas to force a new instance of a specific backend object.
   *
   * @return AbstractBackend
   *    Backend instance.
   */
  static public function getInstance($machine_name, $reset = FALSE) {
    if (!isset(self::$instances[$machine_name]) || $reset) {
      /** @var BackendConfiguration $configuration */
      $configuration = self::loadConfiguration($machine_name);

      $plugin_manager = PluginManager::getInstance('backend');
      $backend_class = $plugin_manager->getPlugin($configuration->getPlugin())->getClass();
      $response_class = $plugin_manager->getComponent($configuration->getResponse())->getClass();
      $formatter_class = $plugin_manager->getComponent($configuration->getFormatter())->getClass();

      foreach ([$backend_class, $response_class, $formatter_class] as $class) {
        if (!class_exists($class)) {
          throw new \InvalidArgumentException("Class $class does not exists");
        }
      }
      self::$instances[$machine_name] = new $backend_class($configuration, new $response_class(), new $formatter_class());
    }
    return self::$instances[$machine_name];
  }

  /**
   * Load configuration from database.
   *
   * @param string $machine_name
   *    Backend configuration machine name.
   *
   * @return AbstractConfiguration
   *    Configuration object.
   */
  static public function loadConfiguration($machine_name) {
    return ConfigurationFactory::load('integration_backend', $machine_name);
  }

}

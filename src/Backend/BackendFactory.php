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
use Drupal\integration\Backend\Authentication\AbstractAuthentication;

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
      $formatter_class = $plugin_manager->getComponent($configuration->getFormatter())->getClass();
      $authentication_class = $plugin_manager->getComponent($configuration->getAuthentication())->getClass();

      self::$instances[$machine_name] = new $backend_class(
        $configuration,
        new $formatter_class(),
        new $authentication_class($configuration));
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

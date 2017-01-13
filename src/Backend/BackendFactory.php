<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\BackendFactory.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Plugins\PluginManager;

/**
 * Interface BackendFactory.
 *
 * @package Drupal\integration\Backend
 */
class BackendFactory {

  /**
   * Default plugin for a newly created backend.
   */
  const DEFAULT_PLUGIN = 'memory_backend';

  /**
   * Default authentication plugin for a newly created backend.
   */
  const DEFAULT_AUTHENTICATION = 'no_authentication';

  /**
   * Default formatter plugin for a newly created backend.
   */
  const DEFAULT_FORMATTER = 'json_formatter';

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
   * Create backend instance.
   *
   * Use this method when operating backends pragmatically, i.e. when you
   * do not have configuration stored in database or code.
   *
   * @param string $machine_name
   *    Backend configuration machine name.
   * @param string $plugin
   *    Plugin machine name.
   * @param string $authentication
   *    Authentication plugin machine name.
   * @param string $formatter
   *    Formatter plugin machine name.
   *
   * @return AbstractBackend
   *    Backend instance.
   */
  static public function create($machine_name, $plugin = self::DEFAULT_PLUGIN, $authentication = self::DEFAULT_AUTHENTICATION, $formatter = self::DEFAULT_FORMATTER) {
    /** @var BackendConfiguration $configuration */
    $configuration = ConfigurationFactory::create('backend', $machine_name);
    $configuration->setPlugin($plugin);
    $configuration->setAuthentication($authentication);
    $configuration->setFormatter($formatter);
    return self::getInstance($machine_name);
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

<?php

/**
 * @file
 * Contains ConsumerFactory.
 */

namespace Drupal\integration_consumer;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Plugins\PluginManager;
use Drupal\integration_consumer\Configuration\ConsumerConfiguration;

/**
 * Interface ConsumerFactory.
 *
 * @package Drupal\integration_consumer
 */
class ConsumerFactory {

  /**
   * Default plugin for a newly created consumer.
   */
  const DEFAULT_PLUGIN = 'node_consumer';

  /**
   * Instantiate and return a consumer object given its configuration.
   *
   * @param string $machine_name
   *    Consumer configuration machine name.
   *
   * @return AbstractConsumer
   *    Consumer instance.
   */
  static public function getInstance($machine_name) {
    /** @var ConsumerConfiguration $configuration */
    $configuration = self::loadConfiguration($machine_name);

    $plugin_manager = PluginManager::getInstance('consumer');
    $plugin = $configuration->getPlugin();

    /** @var AbstractConsumer $consumer_class */
    $consumer_class = $plugin_manager->getPlugin($plugin)->getClass();

    return $consumer_class::getInstance($machine_name);
  }

  /**
   * Create consumer instance.
   *
   * Use this method when operating consumers pragmatically, i.e. when you
   * do not have configuration stored in database or code.
   *
   * @param string $machine_name
   *    Consumer configuration machine name.
   * @param $backend_name
   *    Backend configuration machine name.
   * @param string $plugin
   *    Plugin machine name.
   *
   * @return AbstractConsumer
   *    Consumer instance.
   *
   * @throws \Drupal\integration\Exceptions\ConfigurationException
   *    Throws ConfigurationException.
   */
  static public function create($machine_name, $backend_name, $plugin = self::DEFAULT_PLUGIN) {
    // Try to load backend, throws ConfigurationException if not found.
    $backend = ConfigurationFactory::load('integration_backend', $backend_name);
    /** @var ConsumerConfiguration $configuration */
    $configuration = ConfigurationFactory::create('consumer', $machine_name);
    $configuration->setPlugin($plugin);
    $configuration->setBackend($backend->getMachineName());
    return self::getInstance($machine_name);
  }

  /**
   * Load configuration from database.
   *
   * @param string $machine_name
   *    Consumer configuration machine name.
   *
   * @return AbstractConfiguration
   *    Configuration object.
   */
  static public function loadConfiguration($machine_name) {
    return ConfigurationFactory::load('integration_consumer', $machine_name);
  }

}

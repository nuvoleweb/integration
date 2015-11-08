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
   * Instantiate and return a consumer object given its configuration.
   *
   * @param string $machine_name
   *    Consumer configuration machine name.
   *
   * @return \Drupal\integration_consumer\AbstractConsumer
   *    Consumer instance.
   */
  static public function getInstance($machine_name) {
    /** @var ConsumerConfiguration $configuration */
    $configuration = self::loadConfiguration($machine_name);

    $plugin_manager = PluginManager::getInstance('consumer');
    $plugin = $configuration->getPlugin();

    /** @var AbstractConsumer $consumer_class */
    $consumer_class = $plugin_manager->getPlugin($plugin)->getClass();

    if (!class_exists($consumer_class)) {
      throw new \InvalidArgumentException(t('Class @class does not exists', ['class' => $consumer_class]));
    }

    return $consumer_class::getInstance($machine_name);
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

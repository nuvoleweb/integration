<?php

/**
 * @file
 * Contains ProducerFactory.
 */

namespace Drupal\integration_producer;


use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Document\Document;

use Drupal\integration\Plugins\PluginManager;
use Drupal\integration_producer\Configuration\ProducerConfiguration;

/**
 * Interface ProducerFactory.
 *
 * @package Drupal\integration_producer
 */
class ProducerFactory {

  /**
   * Default plugin for a newly created backend.
   */
  const DEFAULT_PLUGIN = 'node_producer';

  /**
   * Instantiate and return a producer object given its configuration.
   *
   * @param string $machine_name
   *    Producer configuration machine name.
   *
   * @return AbstractProducer
   *    Producer instance.
   */
  static public function getInstance($machine_name) {
    /** @var ProducerConfiguration $configuration */
    $configuration = self::loadConfiguration($machine_name);

    $plugin_manager = PluginManager::getInstance('producer');
    $plugin = $configuration->getPlugin();

    $producer_class = $plugin_manager->getPlugin($plugin)->getClass();
    $entity_wrapper = new EntityWrapper\EntityWrapper($plugin_manager->getPlugin($plugin)->getEntityType());
    $document = new Document();

    return new $producer_class($configuration, $entity_wrapper, $document);
  }

  /**
   * Create producer instance.
   *
   * Use this method when operating producers pragmatically, i.e. when you
   * do not have configuration stored in database or code.
   *
   * @param string $machine_name
   *    Producer configuration machine name.
   * @param string $plugin
   *    Plugin machine name.
   *
   * @return AbstractProducer
   *    Producer instance.
   */
  static public function create($machine_name, $plugin = self::DEFAULT_PLUGIN) {
    /** @var ProducerConfiguration $configuration */
    $configuration = ConfigurationFactory::create('producer', $machine_name);
    $configuration->setPlugin($plugin);
    return self::getInstance($machine_name);
  }

  /**
   * Load configuration from database.
   *
   * @param string $machine_name
   *    Producer configuration machine name.
   *
   * @return AbstractConfiguration
   *    Configuration object.
   */
  static public function loadConfiguration($machine_name) {
    return ConfigurationFactory::load('integration_producer', $machine_name);
  }

}

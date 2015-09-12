<?php

/**
 * @file
 * Contains ProducerFactory.
 */

namespace Drupal\integration_producer;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Document\Document;
use Drupal\integration\PluginManager;
use Drupal\integration_producer\Configuration\ProducerConfiguration;

/**
 * Interface ProducerFactory.
 *
 * @package Drupal\integration_producer
 */
class ProducerFactory {

  /**
   * Instantiate and return a producer object given its configuration.
   *
   * @param string $machine_name
   *    Producer configuration machine name.
   *
   * @return \Drupal\integration_producer\AbstractProducer
   *    Producer instance.
   */
  static public function getInstance($machine_name) {
    /** @var ProducerConfiguration $configuration */
    $configuration = self::loadConfiguration($machine_name);

    $plugin_manager = PluginManager::getInstance('producer');
    $plugin = $configuration->getPlugin();

    $producer_class = $plugin_manager->getClass($plugin);
    $entity_wrapper = new EntityWrapper\EntityWrapper($plugin_manager->getEntityType($plugin));
    $document = new Document();

    if (!class_exists($producer_class)) {
      throw new \InvalidArgumentException("Class $producer_class does not exists");
    }

    $entity_wrapper = new EntityWrapper\EntityWrapper($configuration->getType());
    $document = new Document();
    return new $producer_class($configuration, $entity_wrapper, $document);
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

<?php

/**
 * @file
 * Contains \Drupal\integration\ResourceSchema\ResourceSchemaFactory.
 */

namespace Drupal\integration\ResourceSchema;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Plugins\PluginManager;

/**
 * Interface ResourceSchemaFactory.
 *
 * @package Drupal\integration_resource_schema
 */
class ResourceSchemaFactory {

  /**
   * Default plugin for a newly created resource schema.
   */
  const DEFAULT_PLUGIN = 'raw_resource_schema';

  /**
   * Instantiate and return a resource schema object given its configuration.
   *
   * @param string $machine_name
   *    Resource schema configuration machine name.
   *
   * @return AbstractResourceSchema
   *    Resource schema instance.
   */
  static public function getInstance($machine_name) {
    /** @var ResourceSchemaConfiguration $configuration */
    $configuration = self::loadConfiguration($machine_name);

    $plugin_manager = PluginManager::getInstance('resource_schema');
    $plugin = $configuration->getPlugin();

    $resource_schema_class = $plugin_manager->getPlugin($plugin)->getClass();

    return new $resource_schema_class($configuration);
  }

  /**
   * Load configuration from database.
   *
   * @param string $machine_name
   *    Resource schema configuration machine name.
   *
   * @return AbstractConfiguration
   *    Configuration object.
   */
  static public function loadConfiguration($machine_name) {
    return ConfigurationFactory::load('integration_resource_schema', $machine_name);
  }

  /**
   * Create resource schema instance.
   *
   * Use this method when operating resource schemas  pragmatically, i.e. when
   * you do not have configuration stored in database or code.
   *
   * @param string $machine_name
   *    Resource schema configuration machine name.
   * @param string $plugin
   *    Plugin machine name.
   *
   * @return AbstractResourceSchema
   *    Resource schema instance.
   */
  static public function create($machine_name, $plugin = self::DEFAULT_PLUGIN) {
    /** @var ResourceSchemaConfiguration $configuration */
    $configuration = ConfigurationFactory::create('resource_schema', $machine_name);
    $configuration->setPlugin($plugin);
    return self::getInstance($machine_name);
  }

}

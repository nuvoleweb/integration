<?php

/**
 * @file
 * Contains ResourceSchemaFactory.
 */

namespace Drupal\integration\ResourceSchema;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Plugins\PluginManager;
use Drupal\integration\ResourceSchema\Configuration\ResourceSchemaConfiguration;

/**
 * Interface ResourceSchemaFactory.
 *
 * @package Drupal\integration_resource_schema
 */
class ResourceSchemaFactory {

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

    if (!class_exists($resource_schema_class)) {
      throw new \InvalidArgumentException(t('Class @class does not exists', ['class' => $resource_schema_class]));
    }

    return new $resource_schema_class($configuration);
  }

  /**
   * Load configuration from database.
   *
   * @param string $machine_name
   *    ResourceSchema configuration machine name.
   *
   * @return AbstractConfiguration
   *    Configuration object.
   */
  static public function loadConfiguration($machine_name) {
    return ConfigurationFactory::load('integration_resource_schema', $machine_name);
  }

}

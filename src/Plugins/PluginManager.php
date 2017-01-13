<?php

/**
 * @file
 * Contains \Drupal\integration\Plugins\PluginManager.
 */

namespace Drupal\integration\Plugins;

use Drupal\integration\Plugins\Exceptions\PluginManagerException;

/**
 * Plugin manager.
 *
 * The integration module is built on the following plugin types:
 *
 *  - Backend plugins, abstracting specific backend implementations.
 *  - Producer plugins, producing documents that can be stored by a backend.
 *  - Consumer plugins, fetching documents from a backend and saving them as
 *    Drupal entities.
 *  - Resource schema plugins, defining the schema documents need to comply with
 *    in order to be able to be produced, stored and consumer by the plugins
 *    above.
 *
 * Each plugin type may have several plugin components, depending on the use
 * case they are supposed to satisfy.
 *
 * The aim of this class is to collect plugin and component definitions and to
 * provide convenience methods for inspecting their properties.
 *
 * Plugin and component definitions are exposed by implementing the hooks below:
 *
 * @see hook_integration_backend_info()
 * @see hook_integration_backend_info_alter(&$definitions)
 * @see hook_integration_backend_components_info()
 * @see hook_integration_backend_components_info_alter(&$definitions)
 *
 * @see hook_integration_producer_info()
 * @see hook_integration_producer_info_alter(&$definitions)
 * @see hook_integration_producer_components_info()
 * @see hook_integration_producer_components_info_alter(&$definitions)
 *
 * @see hook_integration_consumer_info()
 * @see hook_integration_consumer_info_alter(&$definitions)
 * @see hook_integration_consumer_components_info()
 * @see hook_integration_consumer_components_info_alter(&$definitions)
 *
 * @see hook_integration_resource_schema_info()
 * @see hook_integration_resource_schema_info_alter(&$definitions)
 * @see hook_integration_resource_schema_components_info()
 * @see hook_integration_resource_schema_components_info_alter(&$definitions)
 *
 * @package Drupal\integration\Plugins\PluginManager
 */
class PluginManager {

  /**
   * List of current plugin type definitions.
   *
   * @var array
   */
  private $pluginDefinitions = [];

  /**
   * List of current plugin type component definitions.
   *
   * @var array
   */
  private $componentDefinitions = [];

  /**
   * Current plugin machine name.
   *
   * @var string
   */
  private $plugin;

  /**
   * List of plugin types.
   *
   * @var array
   */
  private $pluginTypes = [
    'backend',
    'consumer',
    'producer',
    'resource_schema',
  ];

  /**
   * Get plugin manager instance.
   *
   * @param string $plugin
   *    Plugin type, as defined in self::$definitions array's keys.
   *
   * @return PluginManager
   *    PluginManager instance for specified plugin type.
   */
  static public function getInstance($plugin) {
    return new self($plugin);
  }

  /**
   * PluginManager constructor.
   *
   * @param string $name
   *    Either a plugin name or its configuration entity type name.
   *
   * @throws PluginManagerException
   *    Throws PluginManagerException if plugin name is not valid.
   */
  public function __construct($name) {
    $this->plugin = str_replace('integration_', '', $name);

    if (!in_array($this->plugin, $this->pluginTypes)) {
      throw new PluginManagerException(t("!name is not a valid plugin type", ['!name' => $this->plugin]));
    }

    $this->pluginDefinitions = module_invoke_all("integration_{$this->plugin}_info");
    drupal_alter("integration_{$this->plugin}_info", $this->pluginDefinitions);

    $this->componentDefinitions = module_invoke_all("integration_{$this->plugin}_components_info");
    drupal_alter("integration_{$this->plugin}_components_info", $this->componentDefinitions);
  }

  /**
   * Get plugin definitions.
   *
   * @return array
   *    List of plugin definitions.
   *
   * @see hook_integration_backend_info()
   * @see hook_integration_producer_info()
   * @see hook_integration_consumer_info()
   * @see hook_integration_resource_schema_info()
   */
  public function getPluginDefinitions() {
    return $this->pluginDefinitions;
  }

  /**
   * Return components definitions for current plugin type.
   *
   * @param string $type
   *    Type of component to return definitions for.
   *
   * @return array
   *    Component definitions of a specific type, if any, all otherwise.
   *
   * @see hook_integration_backend_components_info()
   * @see hook_integration_producer_components_info()
   * @see hook_integration_consumer_components_info()
   */
  public function getComponentDefinitions($type = NULL) {
    if ($type) {
      return array_filter($this->componentDefinitions, function ($definition) use ($type) {
        return $definition['type'] == $type;
      });
    }
    return $this->componentDefinitions;
  }

  /**
   * Get plugin definition object.
   *
   * @param string $plugin
   *    Plugin name.
   *
   * @return PluginDefinition
   *    Plugin definition object.
   */
  public function getPlugin($plugin) {
    return new PluginDefinition($this->pluginDefinitions[$plugin]);
  }

  /**
   * Get component definition object.
   *
   * @param string $component
   *    Component name.
   *
   * @return ComponentDefinition
   *    Component definition object
   */
  public function getComponent($component) {
    return new ComponentDefinition($this->componentDefinitions[$component]);
  }

}

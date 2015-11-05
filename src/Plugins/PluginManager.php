<?php

/**
 * @file
 * Contains \Drupal\integration\Plugins\PluginManager.
 */

namespace Drupal\integration\Plugins;

/**
 * Class PluginManager.
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
   */
  public function __construct($name) {
    $this->plugin = str_replace('integration_', '', $name);

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

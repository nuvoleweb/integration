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
  private $plugin_definitions = array();

  /**
   * List of current plugin type component definitions.
   *
   * @var array
   */
  private $component_definitions = array();

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

    $this->plugin_definitions = module_invoke_all("integration_{$this->plugin}_info");
    drupal_alter("integration_{$this->plugin}_info", $this->plugin_definitions);

    $this->component_definitions = module_invoke_all("integration_{$this->plugin}_components_info");
    drupal_alter("integration_{$this->plugin}_info", $this->component_definitions);
  }

  /**
   * @return array
   */
  public function getPluginDefinitions() {
    return $this->plugin_definitions;
  }

  /**
   * Return components definitions for current plugin type.
   *
   * @param string $type
   *    Type of component to return definitions for.
   *
   * @return array
   *    Component definitions of a specific type, if any, all otherwise.
   */
  public function getComponentDefinitions($type = NULL) {
    if ($type) {
      return array_filter($this->component_definitions, function ($definition) use ($type) {
        return $definition['type'] == $type;
      });
    }
    return $this->component_definitions;
  }

  /**
   * @param string $plugin
   *    Plugin name.
   *
   * @return PluginDefinition
   */
  public function getPlugin($plugin) {
    return new PluginDefinition($this->plugin_definitions[$plugin]);
  }

  /**
   * @param string $component
   *    Component name.
   *
   * @return ComponentDefinition
   */
  public function getComponent($component) {
    return new ComponentDefinition($this->component_definitions[$component]);
  }

}

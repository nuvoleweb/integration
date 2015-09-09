<?php

/**
 * @file
 * Contains \Drupal\integration\PluginManager.
 */

namespace Drupal\integration;

/**
 * Class PluginManager.
 *
 * @package Drupal\integration
 */
class PluginManager {

  /**
   * List of available plugins, along with their components.
   *
   * @var array
   */
  private $definitions = array();

  /**
   * Current plugin machine name.
   *
   * @var string
   */
  private $plugin;

  /**
   * Current component machine name.
   *
   * @var string
   */
  private $component = NULL;

  /**
   * Get plugin manager instance.
   *
   * @param string $plugin
   *    Plugin type, as defined in self::$plugins array's keys.
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
    $this->definitions = integration_get_integration_plugins();
  }

  /**
   * Return current plugin definition array.
   *
   * @return array
   *    Current plugin definition array.
   */
  public function getPluginDefinition() {
    return $this->definitions[$this->plugin];
  }

  /**
   * Set current plugin component.
   *
   * @param string $component
   *    Set current plugin component machine name.
   *
   * @return $this
   */
  public function setComponent($component) {
    $this->component = $component;
    return $this;
  }

  /**
   * Return list of current plugin components.
   *
   * @return array
   *    List of current plugin components.
   */
  public function getComponents() {
    return array_keys($this->definitions[$this->plugin]['components']);
  }

  /**
   * Get provided human readable label of provided component.
   *
   * @return string
   *    Component human readable label.
   */
  public function getComponentLabel($component) {
    return $this->definitions[$this->plugin]['components'][$component];
  }

  /**
   * Get result of info hook for current plugin and component machine name.
   *
   * @return array
   *    Array of information about current plugin and component machine name.
   */
  public function getInfo() {
    $function_name = $this->buildInfoHookName();
    return $function_name();
  }

  /**
   * Get current plugin label.
   *
   * @return string
   *    Current plugin label.
   */
  public function getLabel($name) {
    $info = $this->getInfo();
    return $info[$name]['label'];
  }

  /**
   * Get current plugin class.
   *
   * @param string $name
   *    Plugin or component name.
   *
   * @return string
   *    Current plugin class.
   */
  public function getClass($name) {
    $info = $this->getInfo();
    return $info[$name]['class'];
  }

  /**
   * Get form handler class, if any.
   *
   * @param string $name
   *    Plugin or component name.
   *
   * @return string
   *    Form handler class, if any.
   */
  public function getFormHandler($name) {
    $info = $this->getInfo();
    return (isset($info[$name]['form handler'])) ? $info[$name]['form handler'] : FALSE;
  }

  /**
   * Get current plugin description.
   *
   * @param string $name
   *    Plugin or component name.
   *
   * @return string
   *    Current plugin description.
   */
  public function getDescription($name) {
    $info = $this->getInfo();
    return $info[$name]['description'];
  }

  /**
   * Format current info results as a Form API #options array.
   *
   * @return array
   *    Form API select #options array.
   */
  public function getFormOptions() {
    $info = $this->getInfo();
    $options = array();
    foreach ($info as $name => $definition) {
      $options[$name] = $definition['label'];
    }
    return $options;
  }

  /**
   * Build info hook name give current plugin and component machine name.
   *
   * @return string
   *    Full info hook name.
   */
  private function buildInfoHookName() {
    $parts = array('integration', $this->plugin, 'get');
    $parts[] = $this->component ? $this->component : $this->plugin;
    $parts[] = 'info';
    return implode('_', $parts);
  }

}

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
  private $plugins = array();

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
   * @param string $plugin
   *    Plugin machine name.
   */
  public function __construct($plugin) {
    $this->plugins = $this->pluginDefinitions();
    $this->plugin = $plugin;
  }

  /**
   * Return plugin definition array.
   *
   * @return array
   *    Plugin definition array.
   */
  private function pluginDefinitions() {
    // @todo: this should be a hook defining plugins outside this class.
    // we could use hook_views_plugins() as model and refactor the whole thing.
    return array(
      'backend' => array(
        'components' => array(
          'response_handler' => t('Response handler'),
          'formatter_handler' => t('Formatter handler'),
          'authentication_handler' => t('Authentication handler'),
        ),
      ),
      'consumer' => array(
        'components' => array(
          'mapping_handler' => t('Mapping handler'),
        ),
      ),
      'producer' => array(
        'components' => array(
          'field_handler' => t('Field handler'),
        ),
      ),
    );
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
    return array_keys($this->plugins[$this->plugin]['components']);
  }

  /**
   * Get provided human readable label of provided component.
   *
   * @return string
   *    Component human readable label.
   */
  public function getComponentLabel($component) {
    return $this->plugins[$this->plugin]['components'][$component];
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
   * Check weather the given component is configurable or not.
   *
   * @param string $type
   *    Component type.
   *
   * @return bool
   *    TRUE if the given component is configurable or not, FALSE otherwise.
   */
  public function isComponentConfigurable($type) {
    $info = $this->getInfo();
    if (isset($info[$type]['configuration class'])) {
      if (!class_exists($info[$type]['configuration class'])) {
        throw new \InvalidArgumentException(t('Component configuration class not found.'));
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get component configuration class.
   *
   * @param string $type
   *    Component type.
   *
   * @return string|FALSE
   *    Configuration class name if configurable, FALSE otherwise.
   */
  public function getComponentConfigurationClass($type) {
    if ($this->isComponentConfigurable($type)) {
      $info = $this->getInfo();
      return $info[$type]['configuration class'];
    }
    return FALSE;
  }

  /**
   * Format current info results as Form API radio buttons.
   *
   * @param string $title
   *    Form element #title.
   * @param mixed $default_value
   *    Form element #default_value.
   * @param bool|FALSE $required
   *    Form element #required.
   *
   * @return array
   *    Form API radio buttons element.
   */
  public function getFormRadios($title, $default_value, $required = FALSE) {
    $options = $this->getFormOptions();

    $element = array(
      '#type' => 'radios',
      '#title' => $title,
      '#default_value' => $default_value,
      '#options' => $options,
      '#required' => $required,
    );
    foreach (array_keys($options) as $name) {
      $element[$name] = array('#description' => $this->getDescription($name));
    }
    return $element;
  }

  /**
   * Build info getter name give current plugin and component machine name.
   *
   * @return string
   *    Full info getter name.
   */
  private function buildInfoHookName() {
    $parts = array('integration', $this->plugin, 'get');
    $parts[] = $this->component ? $this->component : $this->plugin;
    $parts[] = 'info';
    return implode('_', $parts);
  }

}

<?php

/**
 * @file
 * Contains ConsumerConfiguration.
 */

namespace Drupal\integration\Configuration;

/**
 * Class AbstractConfiguration.
 *
 * @package Drupal\integration\Configuration
 */
abstract class AbstractConfiguration extends \Entity implements ConfigurationInterface {

  /**
   * Configuration human readable name.
   *
   * @var string
   */
  public $name;

  // @codingStandardsIgnoreStart
  /**
   * Configuration machine name.
   *
   * @var string
   */
  public $machine_name;
  // @codingStandardsIgnoreEnd

  /**
   * Plugin name, as returned by hook_integration_PLUGIN_TYPE_info().
   *
   * @var string
   */
  public $plugin;

  /**
   * Weather the configuration is enabled or not.
   *
   * @var bool
   */
  public $enabled;

  /**
   * Configuration export status.
   *
   * @var string
   *
   * @see integration_configuration_status_options_list()
   */
  public $status;

  /**
   * Configuration entity settings.
   *
   * @var array
   *
   * @see integration_entity_property_info_defaults()
   */
  public $settings = [];

  /**
   * List of current error messages resulted by a failed validation.
   *
   * @var array[string]
   */
  protected $errors = [];

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return isset($this->name) ? $this->name : '';
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugin() {
    return isset($this->plugin) ? $this->plugin : '';
  }

  /**
   * {@inheritdoc}
   */
  public function getMachineName() {
    return isset($this->machine_name) ? $this->machine_name : '';
  }

  /**
   * {@inheritdoc}
   */
  public function getEnabled() {
    return isset($this->enabled) ? $this->enabled : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getStatus() {
    return isset($this->status) ? $this->status : ENTITY_CUSTOM;
  }

  /**
   * {@inheritdoc}
   */
  public function isCustom() {
    return $this->getStatus() == ENTITY_CUSTOM;
  }

  /**
   * {@inheritdoc}
   */
  public function isInCode() {
    return $this->getStatus() == ENTITY_IN_CODE;
  }

  /**
   * {@inheritdoc}
   */
  public function isOverridden() {
    return $this->getStatus() == ENTITY_OVERRIDDEN;
  }

  /**
   * {@inheritdoc}
   */
  public function isFixed() {
    return $this->getStatus() == ENTITY_FIXED;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityInfoProperty($name) {
    return isset($this->entityInfo['entity keys'][$name]) ? $this->entityInfo['entity keys'][$name] : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginSetting($name) {
    $settings = isset($this->settings['plugin']) ? $this->settings['plugin'] : [];
    return $this->getSettingValue($name, $settings);
  }

  /**
   * {@inheritdoc}
   */
  public function setPluginSetting($name, $value) {
    $this->settings['plugin'][$name] = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function unsetPluginSetting($name) {
    unset($this->settings['plugin'][$name]);
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginSettings() {
    return isset($this->settings['plugin']) ? $this->settings['plugin'] : [];
  }

  /**
   * {@inheritdoc}
   */
  public function getComponentSetting($component, $name) {
    $settings = isset($this->settings['components'][$component]) ? $this->settings['components'][$component] : [];
    return $this->getSettingValue($name, $settings);
  }

  /**
   * {@inheritdoc}
   */
  public function setComponentSetting($component, $name, $value) {
    $this->settings['components'][$component][$name] = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function unsetComponentSetting($component, $name) {
    unset($this->settings['components'][$component][$name]);
  }

  /**
   * {@inheritdoc}
   */
  public function getComponentSettings($component) {
    return isset($this->settings['components'][$component]) ? $this->settings['components'][$component] : [];
  }

  /**
   * {@inheritdoc}
   */
  public function validate() {
    return empty($this->errors);
  }

  /**
   * {@inheritdoc}
   */
  public function getErrors() {
    return $this->errors;
  }

  /**
   * Helper method to get a setting value given its name in dotted notation.
   *
   * @param string $name
   *    Plugin setting name using dotted notation, such as "a.b.c".
   *    Nested settings can be reached by concatenating them using a dot
   *    as separator, for example:
   *    $value = $configuration->getPluginSetting('a.b.c');
   *    will return 'c' setting value if any, NULL if not set.
   * @param array $settings
   *    Plugin or plugin component setting array.
   *
   * @return mixed|NULL
   *    Plugin or plugin component setting value if any, NULL otherwise.
   */
  private function getSettingValue($name, $settings) {
    $parts = explode('.', $name);

    $walk = function($parts, $settings) use (&$walk) {
      $key = array_shift($parts);
      if (count($parts)) {
        return isset($settings[$key]) ? $walk($parts, $settings[$key]) : NULL;
      }
      else {
        return isset($settings[$key]) ? $settings[$key] : NULL;
      }
    };
    return $walk($parts, $settings);
  }

}

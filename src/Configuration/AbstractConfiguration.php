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
  public $settings = array();

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
    return isset($this->settings['plugin'][$name]) ? $this->settings['plugin'][$name] : NULL;
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
  public function getComponentSetting($component, $name) {
    return isset($this->settings['components'][$component][$name]) ? $this->settings['components'][$component][$name] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setComponentSetting($component, $name, $value) {
    $this->settings['components'][$component][$name] = $value;
  }

}

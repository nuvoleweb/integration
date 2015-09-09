<?php

/**
 * @file
 * Contains \Drupal\integration_ui\AbstractFormHandler.
 */

namespace Drupal\integration_ui;

use Drupal\integration\PluginManager;
use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Class AbstractFormHandler.
 *
 * @package Drupal\integration_ui
 */
abstract class AbstractFormHandler implements FormHandlerInterface {

  /**
   * Configuration entity we are building the form for.
   *
   * @var AbstractConfiguration
   */
  protected $configuration = NULL;

  /**
   * Plugin manager instance.
   *
   * @var PluginManager
   */
  protected $pluginManager = NULL;

  /**
   * AbstractFormHandler constructor.
   *
   * @param AbstractConfiguration $configuration
   *    Configuration entity we are building the form for.
   * @param PluginManager $plugin_manager
   *    Plugin manager object instance.
   */
  public function __construct(AbstractConfiguration $configuration, PluginManager $plugin_manager) {
    $this->configuration = $configuration;
    $this->pluginManager = $plugin_manager;
  }

  /**
   * Return plugin manager instance.
   *
   * @return PluginManager
   *    Plugin manager instance.
   */
  public function getPluginManager() {
    return $this->pluginManager;
  }

  /**
   * Get current configuration entity object.
   *
   * @return AbstractConfiguration
   *    Current configuration entity object.
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function formSubmit(array $form, array &$form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, array &$form_state) {

  }

  /**
   * Format current PluginManager::getInfo() results as list of radio buttons.
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
  protected function getFormRadios($title, $default_value, $required = FALSE) {
    $options = $this->getPluginManager()->getFormOptions();

    $element = array(
      '#type' => 'radios',
      '#title' => $title,
      '#default_value' => $default_value,
      '#options' => $options,
      '#required' => $required,
    );
    foreach (array_keys($options) as $name) {
      $element[$name] = array('#description' => $this->getPluginManager()->getDescription($name));
    }
    return $element;
  }

  /**
   * Return current plugin components form portion.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   * @param string $op
   *    Current form operation.
   */
  protected function componentsForm(array &$form, array &$form_state, $op) {
    $plugin = $this->getPluginManager();

    $form['component'] = array(
      '#type' => 'vertical_tabs',
      '#tree' => FALSE,
    );
    foreach ($plugin->getComponents() as $component) {

      $plugin->setComponent($component);
      $label = $plugin->getComponentLabel($component);

      $form["component_$component"] = array(
        '#type' => 'fieldset',
        '#title' => $label,
        '#collapsible' => TRUE,
        '#group' => 'component',
      );
      $form["component_$component"][$component] = $this->getFormRadios($label, '', TRUE);

      foreach ($plugin->getInfo() as $type => $info) {
        $element = array(
          '#type' => 'fieldset',
          '#title' => t('@component options', array('@component' => $label)),
          '#collapsible' => TRUE,
          '#group' => "component_$component",
        );

        $form_manager = FormHandlerFactory::getInstance($this->getConfiguration(), $component, $type);
        if ($form_manager) {
          $form_manager->form($element, $form_state, $op);
          $form["component_$component"]["{$component}_configuration"] = $element;
        }
      }
    }
  }

}

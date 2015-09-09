<?php

/**
 * @file
 * Contains \Drupal\integration_ui\AbstractFormHandler
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
   * AbstractFormHandler constructor.
   *
   * @param AbstractConfiguration $configuration
   *    Configuration entity we are building the form for.
   */
  public function __construct(AbstractConfiguration $configuration) {
    $this->configuration = $configuration;
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
  public function form(array &$form, array &$form_state, $op) {

  }

  /**
   * Return current plugin components form portion.
   *
   * @param PluginManager $plugin
   *    Current plugin manager being used.
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   * @param string $op
   *    Current form operation.
   */
  public function componentsForm(PluginManager $plugin, array &$form, array &$form_state, $op) {

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
      $form["component_$component"][$component] = $this->getFormRadios($plugin, $label, '', TRUE);

      foreach ($plugin->getInfo() as $type => $info) {
        if ($plugin->isComponentConfigurable($type)) {
          $element = array(
            '#type' => 'fieldset',
            '#title' => t('@component options', array('@component' => $label)),
            '#collapsible' => TRUE,
            '#group' => "component_$component",
          );

          // @todo: make a proper component configuration factory for this.
          $class = $plugin->getComponentConfigurationClass($type);

          /** @var AbstractComponentConfiguration $component_configuration */
          $component_configuration = new $class($this);
//          $component_configuration->form($element, $form_state, $op);

          $form["component_$component"]["{$component}_configuration"] = $element;
        }
      }
    }
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
   * @param PluginManager $plugin_manager
   *    Current plugin manager instance.
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
  public function getFormRadios(PluginManager $plugin_manager, $title, $default_value, $required = FALSE) {
    $options = $plugin_manager->getFormOptions();

    $element = array(
      '#type' => 'radios',
      '#title' => $title,
      '#default_value' => $default_value,
      '#options' => $options,
      '#required' => $required,
    );
    foreach (array_keys($options) as $name) {
      $element[$name] = array('#description' => $plugin_manager->getDescription($name));
    }
    return $element;
  }

}

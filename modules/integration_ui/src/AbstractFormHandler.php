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

  /**
   * Form API helper: return select element.
   *
   * @param string $label
   *    Form element label.
   * @param array $options
   *    Form element options.
   * @param mixed $default
   *    Form element default value.
   * @param bool|TRUE $required
   *    Weather the form element is required or not.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  protected function getSelect($label, array $options, $default = NULL, $required = TRUE) {
    return array(
      '#type' => 'select',
      '#title' => $label,
      '#default_value' => $default,
      '#options' => $options,
      '#required' => $required,
    );
  }

  /**
   * Form API helper: return text field element.
   *
   * @param string $label
   *    Form element label.
   * @param mixed $default
   *    Form element default value.
   * @param bool|TRUE $required
   *    Weather the form element is required or not.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  protected function getTextField($label, $default = NULL, $required = TRUE) {
    return array(
      '#type' => 'textfield',
      '#title' => $label,
      '#default_value' => $default,
      '#required' => $required,
    );
  }

  /**
   * Form API helper: return form elements wrapped into a table.
   *
   * @param array $header
   *    Table header.
   * @param array $rows
   *    Table rows as an array of Form API elements.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   *
   * @see theme_integration_form_table()
   */
  protected function getFormTable(array $header, array $rows) {
    return array(
      '#theme' => 'integration_form_table',
      '#header' => $header,
      'rows' => $rows,
    );
  }

  /**
   * Form API helper: return fieldset element.
   *
   * @param string $label
   *    Form element label.
   * @param bool|FALSE $tree
   *    Weather the form element is to be treated as a tree.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  protected function getFieldset($label, $tree = FALSE) {
    return array(
      '#type' => 'fieldset',
      '#title' => $label,
      '#collapsable' => TRUE,
      '#collapsed' => TRUE,
      '#tree' => $tree,
    );
  }

  /**
   * Get form value from $form_state['values'] array.
   *
   * @param array $form_state
   *    Form state array.
   * @param string $name
   *    Form state value name.
   *
   * @return mixed|FALSE
   *    Return form value if any, FALSE otherwise.
   */
  protected function getFormValue(array &$form_state, $name) {
    if (isset($form_state['values'][$name])) {
      return $form_state['values'][$name];
    }
    return FALSE;
  }

  /**
   * Extract select options from a two-levels array.
   *
   * @param array $array
   *    Two-levels array to extract select options from.
   * @param string $label_key
   *    Array key of array item to be used as label.
   *
   * @return array
   *    Input array formatted to be consumable by a Form API select.
   */
  protected function extractSelectOptions(array $array, $label_key) {
    $values = array();
    foreach ($array as $key => $value) {
      $values[$key] = $value[$label_key];
    }
    return $values;
  }

}

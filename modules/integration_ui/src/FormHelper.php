<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHelper.
 */

namespace Drupal\integration_ui;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Plugins\PluginManager;
use Drupal\integration_consumer\Configuration\ConsumerConfiguration;
use Drupal\integration_producer\Configuration\ProducerConfiguration;

/**
 * Class FormHelper.
 *
 * @package Drupal\integration_ui
 */
class FormHelper {

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
  static public function select($label, array $options, $default = NULL, $required = TRUE) {
    return [
      '#type' => 'select',
      '#title' => $label,
      '#default_value' => $default,
      '#options' => $options,
      '#required' => $required,
    ];
  }

  /**
   * Form API helper: return select element with hidden label.
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
  static public function hiddenLabelSelect($label, array $options, $default = NULL, $required = TRUE) {
    $element = self::select($label, $options, $default, $required);
    $element['#title_display'] = 'invisible';
    return $element;
  }

  /**
   * Form API helper: return checkboxes element.
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
  static public function checkboxes($label, array $options, $default = NULL, $required = TRUE) {
    return [
      '#type' => 'checkboxes',
      '#title' => $label,
      '#options' => $options,
      '#required' => $required,
      '#default_value' => $default,
    ];
  }

  /**
   * Form API helper: return checkboxes element with hidden label.
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
  static public function hiddenLabelCheckboxes($label, array $options, $default = NULL, $required = TRUE) {
    $element = self::checkboxes($label, $options, $default, $required);
    $element['#title_display'] = 'invisible';
    return $element;
  }


  /**
   * Extract select options from a two-levels array.
   *
   * @param array $array
   *    Two-levels array to extract select options from.
   * @param string $label_key
   *    Array item's key to be used as label.
   *
   * @return array
   *    Input array formatted to be consumable by a Form API select.
   */
  static public function asOptions(array $array, $label_key = 'label') {
    $values = [];
    foreach ($array as $key => $value) {
      $values[$key] = $value[$label_key];
    }
    return $values;
  }

  /**
   * Return a step submit used to submit partially build form.
   *
   * @param string $label
   *    Form element label.
   * @param string $name
   *    Form element name.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function stepSubmit($label, $name = '') {
    $element = [
      '#type' => 'submit',
      '#value' => $label,
      '#name' => $name,
      '#field' => $name,
      '#limit_validation_errors' => [],
      '#submit' => ['integration_ui_entity_form_submit'],
    ];
    if ($name) {
      $element['#name'] = $name;
    }
    return $element;
  }

  /**
   * Form API helper: return fieldset element.
   *
   * @param string $label
   *    Form element label.
   * @param bool|FALSE $tree
   *    Weather the form element is to be treated as a tree.
   * @param string $group
   *    Fieldset group, if any.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function fieldset($label, $tree = FALSE, $group = '') {
    $element = [
      '#type' => 'fieldset',
      '#title' => $label,
      '#tree' => $tree,
    ];
    if ($group) {
      $element['#group'] = $group;
    }
    return $element;
  }

  /**
   * Form API helper: return vertical tabs element.
   *
   * @param bool|TRUE $tree
   *    Weather the form element is to be treated as a tree.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function verticalTabs($tree = TRUE) {
    return [
      '#type' => 'vertical_tabs',
      '#tree' => $tree,
    ];
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
  static public function inlineFieldset($label, $tree = FALSE) {
    $element = self::fieldset($label, $tree);
    $element['#attributes'] = ['class' => ['container-inline']];
    return $element;
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
  static public function table(array $header, array $rows) {
    return [
      '#theme' => 'integration_form_table',
      '#header' => $header,
      '#tree' => FALSE,
      'rows' => $rows,
    ];
  }

  /**
   * Form API helper: return hidden form element.
   *
   * @param string $value
   *    Hidden form element value.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function hidden($value) {
    return [
      '#value' => $value,
    ];
  }

  /**
   * Form API helper: return markup form element.
   *
   * @param string $markup
   *    Markup value.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function markup($markup) {
    return [
      '#markup' => $markup,
    ];
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
   * @param string $description
   *    Form field description.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function textField($label, $default = NULL, $required = TRUE, $description = '') {
    return [
      '#type' => 'textfield',
      '#title' => $label,
      '#default_value' => $default,
      '#description' => $description,
      '#required' => $required,
    ];
  }

  /**
   * Format current PluginManager::getInfo() results as list of radio buttons.
   *
   * @param string $title
   *    Form element #title.
   * @param array $array
   *    Two-levels array to extract options and descriptions from.
   * @param mixed $default_value
   *    Form element #default_value.
   * @param bool|FALSE $required
   *    Form element #required.
   * @param string $label_key
   *    Array item's key to be used as label.
   * @param string $description_key
   *    Array item's key to be used as description.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function radios($title, array $array, $default_value, $required = FALSE, $label_key = 'label', $description_key = 'description') {
    $options = self::asOptions($array, $label_key);

    $element = [
      '#type' => 'radios',
      '#title' => $title,
      '#default_value' => $default_value,
      '#options' => $options,
      '#required' => $required,
    ];
    foreach ($array as $name => $values) {
      $element[$name] = ['#description' => $values[$description_key]];
    }
    return $element;
  }

  /**
   * Set #tree value.
   *
   * @param bool|TRUE $tree
   *    Weather to consider the current element as a form #tree or not.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function tree($tree = TRUE) {
    return [
      '#tree' => $tree,
    ];
  }

  /**
   * Returns plugin selection form.
   *
   * @param string $label
   *    Form element label.
   * @param AbstractConfiguration $configuration
   *    Current configuration object.
   * @param PluginManager $plugin_manager
   *    Current plugin manager object.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function choosePlugin($label, AbstractConfiguration $configuration, PluginManager $plugin_manager) {
    $options = self::asOptions($plugin_manager->getPluginDefinitions());
    $default = $configuration->getPlugin();

    $form['plugin_container'] = self::inlineFieldset($label);
    $form['plugin_container']['plugin'] = self::hiddenLabelSelect($label, $options, $default);
    $form['plugin_container']['select_plugin'] = self::stepSubmit(t('Select plugin'), 'select_plugin');
    return $form;
  }

  /**
   * Returns entity bundle selection form.
   *
   * @param string $plugin
   *    Current plugin name, as defined in PluginManager::$pluginDefinitions.
   * @param AbstractConfiguration|ProducerConfiguration|ConsumerConfiguration $configuration
   *    Current configuration object.
   * @param PluginManager $plugin_manager
   *    Current plugin manager object.
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function chooseEntityBundle($plugin, AbstractConfiguration $configuration, PluginManager $plugin_manager) {
    $entity_type = $plugin_manager->getPlugin($plugin)->getEntityType();
    $entity_info = entity_get_info($entity_type);
    $options = self::asOptions($entity_info['bundles']);
    $entity_bundle = $configuration->getEntityBundle();

    $form['entity_bundle_container'] = self::inlineFieldset(t('Entity bundle'));
    $form['entity_bundle_container']['entity_bundle'] = self::hiddenLabelSelect(t('Entity bundle'), $options, $entity_bundle);
    $form['entity_bundle_container']['entity_bundle_submit'] = self::stepSubmit(t('Select bundle'), 'entity_bundle_submit');
    return $form;
  }

}

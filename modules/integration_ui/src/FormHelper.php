<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHelper.
 */

namespace Drupal\integration_ui;

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
    return array(
      '#type' => 'select',
      '#title' => $label,
      '#default_value' => $default,
      '#options' => $options,
      '#required' => $required,
    );
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
    return array(
      '#type' => 'checkboxes',
      '#title' => $label,
      '#options' => $options,
      '#required' => $required,
      '#default_value' => $default,
    );
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
    $values = array();
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
    $element = array(
      '#type' => 'submit',
      '#value' => $label,
      '#name' => $name,
      '#limit_validation_errors' => array(),
      '#submit' => array('integration_ui_entity_form_submit'),
    );
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
    $element = array(
      '#type' => 'fieldset',
      '#title' => $label,
      '#tree' => $tree,
    );
    if ($group) {
      $element['#group'] = $group;
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
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function inlineFieldset($label, $tree = FALSE) {
    $element = self::fieldset($label, $tree);
    $element['#attributes'] = array('class' => array('container-inline'));
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
    return array(
      '#theme' => 'integration_form_table',
      '#header' => $header,
      '#tree' => FALSE,
      'rows' => $rows,
    );
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
    return array(
      '#value' => $value,
    );
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
    return array(
      '#markup' => $markup,
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
  static public function textField($label, $default = NULL, $required = TRUE) {
    return array(
      '#type' => 'textfield',
      '#title' => $label,
      '#default_value' => $default,
      '#required' => $required,
    );
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
   *    Form API radio buttons element.
   */
  static public function radios($title, array $array, $default_value, $required = FALSE, $label_key = 'label', $description_key = 'description') {
    $options = self::asOptions($array, $label_key);

    $element = array(
      '#type' => 'radios',
      '#title' => $title,
      '#default_value' => $default_value,
      '#options' => $options,
      '#required' => $required,
    );
    foreach ($array as $name => $values) {
      $element[$name] = array('#description' => $values[$description_key]);
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
   *    Form API radio buttons element.
   */
  static public function tree($tree = TRUE) {
    return array(
      '#tree' => $tree,
    );
  }

}

<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHelper
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
   *    Array key of array item to be used as label.
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
   *
   * @return array
   *    Form element array as expected by Drupal's Form API.
   */
  static public function fieldset($label, $tree = FALSE) {
    return array(
      '#type' => 'fieldset',
      '#title' => $label,
      '#collapsable' => TRUE,
      '#collapsed' => TRUE,
      '#tree' => $tree,
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
      'rows' => $rows,
    );
  }

  // -------------------------------------------------------------

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
  static public function formTextField($label, $default = NULL, $required = TRUE) {
    return array(
      '#type' => 'textfield',
      '#title' => $label,
      '#default_value' => $default,
      '#required' => $required,
    );
  }

  /**
   * Set the dependent field based on the dependee value via AJAX.
   *
   * @param array $form
   *    Current form array.
   * @param mixed $dependee
   *    Dependee element name or array of parents + element name if nested.
   * @param mixed $dependent
   *    Target element name or array of parents + element name if nested.
   */
  static public function setAjaxDependency(array &$form, $dependee, $dependent) {

    $form[$dependee]['#ajax'] = array(
      'callback' => 'integration_ui_form_ajax_callback',
      'wrapper' => 'integration-ui-ajax-wrapper-' . $dependent,
      'target' => $dependent,
    );
    $form[$dependent]['#prefix'] = "<div id='integration-ui-ajax-wrapper-$dependent'>";
    $form[$dependent]['#suffix'] = "</div>";
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
  static public function getFormValue(array &$form_state, $name) {
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
  static public function extractSelectOptions(array $array, $label_key) {
    $values = array();
    foreach ($array as $key => $value) {
      $values[$key] = $value[$label_key];
    }
    return $values;
  }

//  /**
//   * Format current PluginManager::getInfo() results as list of radio buttons.
//   *
//   * @param string $title
//   *    Form element #title.
//   * @param mixed $default_value
//   *    Form element #default_value.
//   * @param bool|FALSE $requiredw
//   *    Form element #required.
//   *
//   * @return array
//   *    Form API radio buttons element.
//   */
//  static public function getFormRadios($title, $default_value, $required = FALSE) {
//    $options = $this->getPluginManager()->getFormOptions();
//
//    $element = array(
//      '#type' => 'radios',
//      '#title' => $title,
//      '#default_value' => $default_value,
//      '#options' => $options,
//      '#required' => $required,
//    );
//    foreach (array_keys($options) as $name) {
//      $element[$name] = array('#description' => $this->getPluginManager()->getDescription($name));
//    }
//    return $element;
//  }
//
//  /**
//   * Return current plugin components form portion.
//   *
//   * @param array $form
//   *    Form array.
//   * @param array $form_state
//   *    Form state array.
//   * @param string $op
//   *    Current form operation.
//   */
//  static public function componentsForm(array &$form, array &$form_state, $op) {
//    $plugin = $this->getPluginManager();
//
//    $form['component'] = array(
//      '#type' => 'vertical_tabs',
//      '#tree' => FALSE,
//    );
//    foreach ($plugin->getComponents() as $component) {
//
//      $plugin->setComponent($component);
//      $label = $plugin->getComponentLabel($component);
//
//      $form["component_$component"] = array(
//        '#type' => 'fieldset',
//        '#title' => $label,
//        '#collapsible' => TRUE,
//        '#group' => 'component',
//      );
//      $form["component_$component"][$component] = $this->getFormRadios($label, '', TRUE);
//
//      foreach ($plugin->getInfo() as $type => $info) {
//        $element = array(
//          '#type' => 'fieldset',
//          '#title' => t('@component options', array('@component' => $label)),
//          '#collapsible' => TRUE,
//          '#group' => "component_$component",
//        );
//
//        $form_manager = FormFactory::getInstance($this->getConfiguration(), $component, $type);
//        if ($form_manager) {
//          $form_manager->form($element, $form_state, $op);
//          $form["component_$component"]["{$component}_configuration"] = $element;
//        }
//      }
//    }
//  }

}

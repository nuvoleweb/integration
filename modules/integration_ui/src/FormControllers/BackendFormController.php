<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormControllers\BackendFormController.
 */

namespace Drupal\integration_ui\FormControllers;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration_ui\AbstractForm;
use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration_ui\FormFactory;
use Drupal\integration_ui\FormHelper;
use Drupal\integration\Plugins\PluginManager;

/**
 * Class BackendFormController.
 *
 * @package Drupal\integration_ui\FormControllers
 */
class BackendFormController extends AbstractForm {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    /** @var BackendConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);
    /** @var PluginManager $plugin_manager */
    $plugin_manager = $this->getPluginManager($form_state);

    $form['plugin_container'] = FormHelper::inlineFieldset(
      t('Producer plugin')
    );
    $form['plugin_container']['plugin'] = FormHelper::hiddenLabelSelect(
      t('Backend plugin'),
      FormHelper::asOptions($plugin_manager->getPluginDefinitions()),
      $configuration->getPlugin()
    );
    $form['plugin_container']['select_plugin'] = FormHelper::stepSubmit(
      t('Select plugin'),
      'select_plugin'
    );

    // Add resource schemas form portion only after setting up the plugin type.
    if ($plugin = $configuration->getPlugin()) {

      $form['resource_container'] = FormHelper::fieldset(
        t('Resource schemas')
      );
      $form['resource_container']['resource_schemas'] = FormHelper::hiddenLabelCheckboxes(
        t('Resource schemas'),
        $this->loadResourceSchemasAsOptions(),
        (array) $configuration->getPluginSetting('resource_schemas')
      );
      $form['resource_container']['select_plugin'] = FormHelper::stepSubmit(
        t('Select resource schemas'),
        'resources_submit'
      );
    }

    // Prompt each resource schema configuration only when they are set.
    if ($resources = (array) $configuration->getPluginSetting('resource_schemas')) {

      $rows = array();
      foreach ($resources as $resource) {
        $resource_schema = ConfigurationFactory::load('integration_resource_schema', $resource);
        $form['resource_settings'][$resource_schema->getMachineName()] = array(
          '#value' => $resource_schema->getMachineName(),
        );
        $row = array();
        $row['resource'] = array('#markup' => $resource_schema->getName());
        $row['resource_backend_settings']['#tree'] = TRUE;
        $row['resource_backend_settings'][$resource_schema->getMachineName()] = $this->buildPluginSettingsForm($form, $form_state, $op);
        $rows[] = $row;
      }

      $header = array(t('Resource schema'), t('Settings'));
      $form['resource_settings'] = FormHelper::table(
        $header,
        $rows
      );
    }

    return;
//
//
//    if ($plugin && $resources) {
//      $this->componentsForm($form, $form_state, $op);
//    }
  }

  /**
   * @return array
   */
  protected function loadResourceSchemasAsOptions() {
    $options = array();
    $resources = entity_load('integration_resource_schema');
    foreach ($resources as $resource) {
      /** @var BackendConfiguration $resource */
      $options[$resource->getMachineName()] = $resource->getName();
    }
    return $options;
  }


  /**
   * Get specific plugin type settings form.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   * @param string $op
   *    Current form operation.
   *
   * @return mixed
   *    Form array.
   */
  protected function buildPluginSettingsForm(array &$form, array &$form_state, $op) {
//    $info = $this->getPluginManager()->getInfo();
    // @todo: fetch this from plugin type form.
    $plugin_settings['endpoint'] = array(
      '#title' => t('Endpoint'),
      '#type' => 'textfield',
    );
    $plugin_settings['changes'] = array(
      '#title' => t('Change feed'),
      '#type' => 'textfield',
    );
    return $plugin_settings;
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
    /** @var BackendConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);
    $plugin = $this->getPluginManager($form_state);

    $form['components'] = array(
      '#type' => 'vertical_tabs',
      '#tree' => TRUE,
    );
    foreach ($plugin->getComponents() as $component) {

      $component_value = '';
      switch ($component) {
        case 'response_handler':
          $component_value = $configuration->getResponse();
          break;

        case 'formatter_handler':
          $component_value = $configuration->getFormatter();
          break;

        case 'authentication_handler':
          $component_value = $configuration->getAuthentication();
          break;
      }

      $plugin->setComponent($component);
      $label = $plugin->getComponentLabel($component);

      $form["component_$component"] = array(
        '#type' => 'fieldset',
        '#title' => $label,
        '#collapsible' => TRUE,
        '#group' => 'components',
        '#tree' => FALSE,
      );

      $options = $this->getPluginManager()->getFormOptions();
      $form["component_$component"][$component] = array(
        '#type' => 'radios',
        '#title' => $label,
        '#default_value' => $component_value,
        '#options' => $options,
        '#required' => FALSE,
      );
      foreach (array_keys($options) as $name) {
        $element[$name] = array('#description' => $this->getPluginManager()->getDescription($name));
      }

      foreach ($plugin->getInfo() as $type => $info) {
        $element = array(
          '#type' => 'fieldset',
          '#title' => t('@component options', array('@component' => $label)),
          '#collapsible' => TRUE,
          '#group' => "component_$component",
        );

        $form_manager = FormFactory::getInstance($this->getConfiguration(), $component, $type);
        if ($form_manager) {
          $form_manager->form($element, $form_state, $op);
          $form["component_$component"]["{$component}_configuration"] = $element;
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, array &$form_state) {
    /** @var BackendConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);

    if (!$configuration->getResponse()) {
      form_set_error('response_handler', t('Response handler cannot be left empty.'));
    }
    if (!$configuration->getFormatter()) {
      form_set_error('formatter_handler', t('Formatter handler cannot be left empty.'));
    }
    if (!$configuration->getAuthentication()) {
      form_set_error('authentication_handler', t('Authentication handler cannot be left empty.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function formSubmit(array $form, array &$form_state) {
    /** @var BackendConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);
    $input = &$form_state['input'];
    $triggering_element = $form_state['triggering_element'];

    switch ($triggering_element['#name']) {

      case 'select_plugin':
        $form_state['rebuild'] = TRUE;
        break;

      case 'resources_submit':
        $values = array();
        foreach (array_filter($input['resource_schemas']) as $value) {
          $values[] = $value;
        }
        $configuration->setPluginSetting('resource_schemas', $values);
        $form_state['rebuild'] = TRUE;
        break;
    }

    if (isset($input['response_handler'])) {
      $configuration->setResponse($input['response_handler']);
    }
    if (isset($input['formatter_handler'])) {
      $configuration->setFormatter($input['formatter_handler']);
    }
    if (isset($input['authentication_handler'])) {
      $configuration->setAuthentication($input['authentication_handler']);
    }
    if (isset($input['resource_backend_settings'])) {
      $configuration->setPluginSetting('resource_backend_settings', $input['resource_backend_settings']);
    }
  }

}

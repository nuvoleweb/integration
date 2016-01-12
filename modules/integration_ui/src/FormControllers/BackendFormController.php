<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormControllers\BackendFormController.
 */

namespace Drupal\integration_ui\FormControllers;

use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration_ui\AbstractForm;
use Drupal\integration_ui\Exceptions\UndefinedFormHandlerException;
use Drupal\integration_ui\FormFactory;
use Drupal\integration_ui\FormHandlers\Backend\AbstractBackendFormHandler;
use Drupal\integration_ui\FormHelper;

/**
 * Class BackendFormController.
 *
 * @method BackendConfiguration getConfiguration(array &$form_state)
 *
 * @package Drupal\integration_ui\FormControllers
 */
class BackendFormController extends AbstractForm {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {

    $configuration = $this->getConfiguration($form_state);
    $plugin_manager = $this->getPluginManager($form_state);
    $form_factory = FormFactory::getInstance('backend');

    // Add plugin type selection.
    $form += FormHelper::choosePlugin(t('Backend plugin'), $configuration, $plugin_manager);

    // Add resource schemas form portion only after setting up the plugin type.
    if ($plugin = $configuration->getPlugin()) {
      $options = $this->getResourceSchemasAsOptions();
      $default_value = (array) $configuration->getPluginSetting('resource_schemas');

      $form['resource_container'] = FormHelper::fieldset(t('Resource schemas'));
      $form['resource_container']['resource_schemas'] = FormHelper::hiddenLabelCheckboxes(t('Resource schemas'), $options, $default_value);
      $form['resource_container']['select_plugin'] = FormHelper::stepSubmit(t('Select resource schemas'), 'resources_submit');
    }

    // Prompt each resource schema configuration only when they are set.
    if ($resources = (array) $configuration->getPluginSetting('resource_schemas')) {

      try {
        /** @var AbstractBackendFormHandler $plugin_handler */
        $plugin_handler = $form_factory->getPluginHandler($plugin);
        $form['backend'] = FormHelper::fieldset(t('Backend settings'), TRUE);
        $plugin_handler->form($form['backend'], $form_state, $op);
      }
      catch (UndefinedFormHandlerException $e) {
      }

      $rows = [];
      foreach ($resources as $machine_name) {
        $element = [];
        $form['resource_settings'][$machine_name] = FormHelper::hidden($machine_name);
        try {
          /** @var AbstractBackendFormHandler $plugin_handler */
          $plugin_handler = $form_factory->getPluginHandler($plugin);
          $plugin_handler->resourceSchemaForm($machine_name, $element, $form_state, $op);
        }
        catch (UndefinedFormHandlerException $e) {
        }

        $row = [];
        $row['resource'] = FormHelper::markup($this->getResourceSchemaLabel($machine_name));
        $row['resource_schema'] = FormHelper::tree();
        $row['resource_schema'][$machine_name] = $element;
        $rows[] = $row;
      }

      $header = [t('Resource schema'), t('Settings')];
      $form['resource_settings'] = FormHelper::table($header, $rows);
    }

    // Add component specific forms.
    if ($plugin && $resources) {
      $components = [
        'authentication_handler' => [
          'label' => t('Authentication handler'),
          'value' => $configuration->getAuthentication(),
        ],
        'formatter_handler' => [
          'label' => t('Formatter handler'),
          'value' => $configuration->getFormatter(),
        ],
      ];

      $form['components'] = FormHelper::verticalTabs();
      foreach ($components as $component_type => $component) {
        $options = $plugin_manager->getComponentDefinitions($component_type);

        $form["component_$component_type"] = FormHelper::fieldset($component['label'], FALSE, 'components');
        $form["component_$component_type"][$component_type] = FormHelper::radios($component['label'], $options, $component['value']);
        $form["component_$component_type"][$component_type]['select_component'] = FormHelper::stepSubmit(t('Select !name', ['!name' => $component['label']]), 'component_submit');

        $component_value = !empty($component['value']) ? $component['value'] : FormHelper::getFirstOption($options);
        if ($component_value) {
          try {
            $element = FormHelper::fieldset(t('Settings'), TRUE, "component_$component_type");
            $form_factory->getComponentHandler($component_value)->form($element, $form_state, $op);
            $form["component_$component_type"]["{$component_type}_configuration"] = $element;
          }
          catch (UndefinedFormHandlerException $e) {
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function formSubmit(array $form, array &$form_state) {
    $configuration = $this->getConfiguration($form_state);
    $input = &$form_state['input'];
    $triggering_element = $form_state['triggering_element'];
    $form_factory = FormFactory::getInstance('backend');

    switch ($triggering_element['#name']) {

      case 'select_plugin':
      case 'component_submit':
        $form_state['rebuild'] = TRUE;
        break;

      case 'resources_submit':
        $values = [];
        foreach (array_filter($input['resource_schemas']) as $value) {
          $values[] = $value;
        }
        $configuration->setPluginSetting('resource_schemas', $values);
        $form_state['rebuild'] = TRUE;
        break;
    }

    if (isset($input['formatter_handler'])) {
      $configuration->setFormatter($input['formatter_handler']);
    }
    if (isset($input['authentication_handler'])) {
      $configuration->setAuthentication($input['authentication_handler']);
      if (!$form_state['rebuild']) {
        try {
          $form_factory->getComponentHandler($input['authentication_handler'])->formSubmit($form, $form_state);
        }
        catch (UndefinedFormHandlerException $e) {
        }
      }
    }
    if (isset($input['backend'])) {
      $configuration->setPluginSetting('backend', $input['backend']);
    }
    if (isset($input['resource_schema'])) {
      $configuration->setPluginSetting('resource_schema', $input['resource_schema']);
    }
  }

}

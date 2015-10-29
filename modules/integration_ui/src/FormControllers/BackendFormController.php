<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormControllers\BackendFormController.
 */

namespace Drupal\integration_ui\FormControllers;

use Drupal\integration_ui\AbstractForm;
use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration_ui\Exceptions\UndefinedFormHandlerException;
use Drupal\integration_ui\FormFactory;
use Drupal\integration_ui\FormHelper;
use Drupal\integration_ui\FormHandlers\Backend\AbstractBackendFormHandler;

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

      try {
        /** @var AbstractBackendFormHandler $plugin_handler */
        $plugin_handler = $form_factory->getPluginHandler($plugin);
        $form['backend'] = FormHelper::fieldset(t('Backend settings'), TRUE);
        $plugin_handler->form($form['backend'], $form_state, $op);
      }
      catch (UndefinedFormHandlerException $e) {
      }
    }

    // Prompt each resource schema configuration only when they are set.
    if ($resources = (array) $configuration->getPluginSetting('resource_schemas')) {

      $rows = array();
      foreach ($resources as $machine_name) {
        $element = array();
        $form['resource_settings'][$machine_name] = FormHelper::hidden($machine_name);
        try {
          /** @var AbstractBackendFormHandler $plugin_handler */
          $plugin_handler = $form_factory->getPluginHandler($plugin);
          $plugin_handler->resourceSchemaForm($machine_name, $element, $form_state, $op);
        }
        catch (UndefinedFormHandlerException $e) {
        }

        $row = array();
        $row['resource'] = FormHelper::markup($this->getResourceSchemaLabel($machine_name));
        $row['resource_schema'] = FormHelper::tree();
        $row['resource_schema'][$machine_name] = $element;
        $rows[] = $row;
      }

      $header = array(t('Resource schema'), t('Settings'));
      $form['resource_settings'] = FormHelper::table($header, $rows);
    }

    // Add component specific forms.
    if ($plugin && $resources) {
      $components = array(
        'response_handler' => array(
          'label' => t('Response handler'),
          'value' => $configuration->getResponse(),
        ),
        'formatter_handler' => array(
          'label' => t('Formatter handler'),
          'value' => $configuration->getFormatter(),
        ),
        'authentication_handler' => array(
          'label' => t('Authentication handler'),
          'value' => $configuration->getAuthentication(),
        ),
      );

      $form['components'] = FormHelper::verticalTabs();
      foreach ($components as $component_type => $component) {
        $options = $plugin_manager->getComponentDefinitions($component_type);

        $form["component_$component_type"] = FormHelper::fieldset($component['label'], FALSE, 'components');
        $form["component_$component_type"][$component_type] = FormHelper::radios($component['label'], $options, $component['value']);

        if ($component['value']) {
          try {
            $element = FormHelper::fieldset(t('Settings'), FALSE, "component_$component_type");
            $form_factory->getComponentHandler($component['value'])->form($element, $form_state, $op);
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
    if (isset($input['backend'])) {
      $configuration->setPluginSetting('backend', $input['backend']);
    }
    if (isset($input['resource_schema'])) {
      $configuration->setPluginSetting('resource_schema', $input['resource_schema']);
    }
  }

}

<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormControllers\BackendFormController.
 */

namespace Drupal\integration_ui\FormControllers;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration_ui\AbstractForm;
use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration_ui\Exceptions\UndefinedFormHandlerException;
use Drupal\integration_ui\FormFactory;
use Drupal\integration_ui\FormHelper;

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
        $this->getResourceSchemasAsOptions(),
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
      foreach ($resources as $machine_name) {
        $element = array();
        $form['resource_settings'][$machine_name] = FormHelper::hidden($machine_name);
        try {
          $form_factory->getPluginHandler($plugin)->form($element, $form_state, $op);
        }
        catch (UndefinedFormHandlerException $e) {
        }

        $row = array();
        $row['resource'] = FormHelper::markup($this->getResourceSchemaLabel($resource));
        $row['resource_backend_settings']['#tree'] = TRUE;
        $row['resource_backend_settings'][$machine_name] = $element;
        $rows[] = $row;
      }

      $header = array(t('Resource schema'), t('Settings'));
      $form['resource_settings'] = FormHelper::table(
        $header,
        $rows
      );
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

      $form['components'] = array(
        '#type' => 'vertical_tabs',
        '#tree' => TRUE,
      );
      foreach ($components as $component_type => $component) {

        $form["component_$component_type"] = FormHelper::fieldset(
          $component['label'],
          FALSE,
          'components'
        );
        $form["component_$component_type"][$component_type] = FormHelper::radios(
          $component['label'],
          $plugin_manager->getComponentDefinitions($component_type),
          $component['value']
        );

        if ($component['value']) {
          try {
            $element = FormHelper::fieldset(
              t('Settings'),
              FALSE,
              "component_$component_type"
            );
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

  /**
   * Load all available resource schema and format them as an #options array.
   *
   * @return array
   *    Form element options.
   */
  protected function getResourceSchemasAsOptions() {
    $options = array();
    $resources = entity_load('integration_resource_schema');
    foreach ($resources as $resource) {
      /** @var BackendConfiguration $resource */
      $options[$resource->getMachineName()] = $resource->getName();
    }
    return $options;
  }

  /**
   * Get resource schema label given its machine name.
   *
   * @param string $resource
   *    Response schema machine name.
   *
   * @return string
   *    Response schema label.
   */
  protected function getResourceSchemaLabel($resource) {
    $resource_schema = ConfigurationFactory::load('integration_resource_schema', $resource);
    return $resource_schema->getName();
  }

}

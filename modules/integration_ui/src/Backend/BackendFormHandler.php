<?php

/**
 * @file
 * Contains \Drupal\integration_ui\Backend\BackendFormHandler.
 */

namespace Drupal\integration_ui\Backend;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration_ui\AbstractFormHandler;
use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration_ui\FormHandlerFactory;

/**
 * Class BackendFormHandler.
 *
 * @package Drupal\integration_ui\Backend
 */
class BackendFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    /** @var BackendConfiguration $configuration */
    $configuration = $this->getConfiguration();

    // Build plugin type form portion.
    $this->buildPluginForm($form, $form_state, $op);

    // Add resource schemas form portion.
    if (TRUE || $plugin = $configuration->getPlugin()) {
      $this->buildResourceSchemaForm($form, $form_state, $op);
    }

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
      $form['resource_settings'] = array(
        '#theme' => 'integration_form_table',
        '#header' => $header,
        'rows' => $rows,
      );
    }

    $this->componentsForm($form, $form_state, $op);
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
   * Helper function: render entity bundle form portion.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   * @param string $op
   *    Current form operation.
   */
  protected function buildPluginForm(array &$form, array &$form_state, $op) {
    /** @var BackendConfiguration $configuration */
    $configuration = $this->getConfiguration();

    // Select producer plugin type.
    $options = $this->getPluginManager()->getFormOptions();
    $form['plugin_container'] = array(
      '#type' => 'fieldset',
      '#title' => t('Producer plugin'),
      '#tree' => FALSE,
      '#attributes' => array('class' => array('container-inline')),
    );
    $form['plugin_container']['plugin'] = array(
      '#type' => 'select',
      '#title' => t('Backend plugin'),
      '#title_display' => 'invisible',
      '#options' => $options,
      '#default_value' => $configuration->getPlugin(),
      '#required' => TRUE,
    );
    $form['plugin_container']['select_plugin'] = array(
      '#type' => 'submit',
      '#value' => t('Select plugin'),
      '#name' => 'select_plugin',
      '#limit_validation_errors' => array(),
      '#submit' => array('integration_ui_entity_form_submit'),
    );
  }

  /**
   * Helper function: render resource schema form portion.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   * @param string $op
   *    Current form operation.
   */
  protected function buildResourceSchemaForm(array &$form, array &$form_state, $op) {
    /** @var BackendConfiguration $configuration */
    $configuration = $this->getConfiguration();

    $options = array();
    $resources = entity_load('integration_resource_schema');
    foreach ($resources as $resource) {
      /** @var BackendConfiguration $resource */
      $options[$resource->getMachineName()] = $resource->getName();
    }

    $form['resource_container'] = array(
      '#type' => 'fieldset',
      '#title' => t('Resource schemas'),
      '#tree' => FALSE,
    );
    $form['resource_container']['resource_schemas'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Resource schemas'),
      '#title_display' => 'invisible',
      '#options' => $options,
      '#required' => TRUE,
      '#default_value' => (array) $configuration->getPluginSetting('resource_schemas'),
    );
    $form['resource_container']['resources_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Select schemas'),
      '#name' => 'resources_submit',
      '#limit_validation_errors' => array(),
      '#submit' => array('integration_ui_entity_form_submit'),
    );
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
    $configuration = $this->getConfiguration();
    $plugin = $this->getPluginManager();

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

        $form_manager = FormHandlerFactory::getInstance($this->getConfiguration(), $component, $type);
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
    $configuration = $this->getConfiguration();

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
    $configuration = $this->getConfiguration();
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

    $configuration->setResponse($input['response_handler']);
    $configuration->setFormatter($input['formatter_handler']);
    $configuration->setAuthentication($input['authentication_handler']);

    if (isset($input['resource_backend_settings'])) {
      $configuration->setPluginSetting('resource_backend_settings', $input['resource_backend_settings']);
    }
  }

}

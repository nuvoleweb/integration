<?php

/**
 * @file
 * Contains \Drupal\integration_producer_ui\ProducerFormHandler.
 */

namespace Drupal\integration_producer_ui;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration_ui\AbstractFormHandler;
use Drupal\integration_producer\Configuration\ProducerConfiguration;

/**
 * Class ProducerFormHandler.
 *
 * @package Drupal\integration_producer_ui
 */
class ProducerFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    /** @var ProducerConfiguration $configuration */
    $configuration = $this->getConfiguration();

    // Build plugin type form portion.
    $this->buildPluginForm($form, $form_state, $op);

    // Select entity bundle based on producer plugin type.
    if ($plugin = $configuration->getPlugin()) {
      $this->buildEntityBundleForm($form, $form_state, $op);
    }

    // Add resource schema form portion.
    if ($entity_bundle = $configuration->getEntityBundle()) {
      $this->buildResourceSchemaForm($form, $form_state, $op);
    }

    // Add field mapping form portion.
    $entity_type = $this->getPluginManager()->getEntityType($plugin);
    $resource = $configuration->getResourceSchema();
    if ($resource && $entity_type && $entity_bundle) {
      $this->buildFieldMappingForm($form, $form_state, $op);
    }
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
    /** @var ProducerConfiguration $configuration */
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
      '#title' => t('Producer plugin'),
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
   * Helper function: render entity bundle form portion.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   * @param string $op
   *    Current form operation.
   */
  protected function buildEntityBundleForm(array &$form, array &$form_state, $op) {
    /** @var ProducerConfiguration $configuration */
    $configuration = $this->getConfiguration();
    $plugin = $configuration->getPlugin();

    $entity_type = $this->getPluginManager()->getEntityType($plugin);
    $entity_info = entity_get_info($entity_type);
    $options = $this->extractSelectOptions($entity_info['bundles'], 'label');
    $entity_bundle = $configuration->getEntityBundle();

    $form['entity_bundle_container'] = array(
      '#type' => 'fieldset',
      '#title' => t('Entity bundle'),
      '#tree' => FALSE,
      '#attributes' => array('class' => array('container-inline')),
    );
    $form['entity_bundle_container']['entity_bundle'] = array(
      '#type' => 'select',
      '#title' => t('Entity bundle'),
      '#title_display' => 'invisible',
      '#options' => $options,
      '#default_value' => $entity_bundle,
      '#required' => TRUE,
    );
    $form['entity_bundle_container']['entity_bundle_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Select bundle'),
      '#name' => 'entity_bundle_submit',
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
    /** @var ProducerConfiguration $configuration */
    $configuration = $this->getConfiguration();

    $options = array();
    $resources = entity_load('integration_resource_schema');
    foreach ($resources as $resource) {
      /** @var ProducerConfiguration $resource */
      $options[$resource->getMachineName()] = $resource->getName();
    }

    $form['resource_container'] = array(
      '#type' => 'fieldset',
      '#title' => t('Resource schema'),
      '#tree' => FALSE,
      '#attributes' => array('class' => array('container-inline')),
    );
    $form['resource_container']['resource'] = array(
      '#type' => 'select',
      '#title' => t('Resource schema'),
      '#title_display' => 'invisible',
      '#options' => $options,
      '#default_value' => '',
      '#required' => TRUE,
    );
    $form['resource_container']['resource_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Select schema'),
      '#name' => 'resource_submit',
      '#limit_validation_errors' => array(),
      '#submit' => array('integration_ui_entity_form_submit'),
    );
  }

  /**
   * Helper function: render field mapping form portion.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   * @param string $op
   *    Current form operation.
   */
  protected function buildFieldMappingForm(array &$form, array &$form_state, $op) {
    /** @var ProducerConfiguration $configuration */
    $configuration = $this->getConfiguration();
    $entity_bundle = $configuration->getEntityBundle();

    /** @var \EntityDrupalWrapper $entity_wrapper */
    $entity_wrapper = entity_metadata_wrapper('node');
    $properties = $entity_wrapper->refPropertyInfo();
    $source_options = array('' => '');
    foreach ($properties['properties'] as $key => $value) {
      $source_options[$key] = t('Property: @label (@machine_name)', array('@label' => $value['label'], '@machine_name' => $key));
    }
    foreach ($properties['bundles'][$entity_bundle]['properties'] as $key => $value) {
      $source_options[$key] = t('Field: @label (@machine_name)', array('@label' => $value['label'], '@machine_name' => $key));
    }
    asort($source_options);

    // @todo: change this by setting proper getters on entity property info.
    $resource = ConfigurationFactory::load('integration_resource_schema', $configuration->getResourceSchema());
    $destination_options = array('' => '') + (array) $resource->getPluginSetting('fields');

    $form['settings'] = array(
      '#tree' => TRUE,
    );
    $rows = array();
    $form['settings']['plugin'] = array(
      '#tree' => FALSE,
    );
    $mapping = (array) $configuration->getPluginSetting('mapping');

    foreach ($mapping as $source => $destination) {
      $form['settings']['plugin']['mapping'][$source] = array(
        '#value' => $destination,
      );
      $row = array();
      $row['source'] = array('#markup' => $source_options[$source]);
      $row['destination'] = array('#markup' => $destination_options[$destination]);
      $row['remove_mapping'] = array(
        '#type' => 'submit',
        '#value' => t('Remove'),
        '#name' => 'remove_mapping',
        '#field' => $source,
        '#limit_validation_errors' => array(),
        '#submit' => array('integration_ui_entity_form_submit'),
      );
      $rows[] = $row;
    }

    $rows[] = array(
      'source' => array(
        '#type' => 'select',
        '#options' => $source_options,
        '#default_value' => '',
      ),
      'destination' => array(
        '#type' => 'select',
        '#options' => $destination_options,
        '#default_value' => '',
      ),
      'add_field_mapping' => array(
        '#type' => 'submit',
        '#value' => t('Add mapping'),
        '#name' => 'add_field_mapping',
        '#limit_validation_errors' => array(),
        '#submit' => array('integration_ui_entity_form_submit'),
      ),
    );

    $header = array(t('Source'), t('Destination'), '');
    $form['mapping'] = array(
      '#theme' => 'integration_form_table',
      '#header' => $header,
      '#tree' => FALSE,
      'rows' => $rows,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, array &$form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function formSubmit(array $form, array &$form_state) {
    /** @var ProducerConfiguration $configuration */
    $configuration = $this->getConfiguration();
    $input = &$form_state['input'];
    $triggering_element = $form_state['triggering_element'];

    switch ($triggering_element['#name']) {

      // Add field to plugin settings.
      case 'select_plugin':
      case 'entity_bundle_submit':
      case 'resource_submit':
        $form_state['rebuild'] = TRUE;
        break;

      case 'add_field_mapping':
        $source = $input['source'];
        $destination = $input['destination'];
        // @todo: expose producer configuration methods dealing with mapping.
        $configuration->settings['plugin']['mapping'][$source] = $destination;
        $form_state['rebuild'] = TRUE;
        break;

      // Remove field from plugin settings.
      case 'remove_mapping':
        $field_name = $triggering_element['#field'];
        // @todo: expose producer configuration methods dealing with mapping.
        unset($configuration->settings['plugin']['mapping'][$field_name]);
        $form_state['rebuild'] = TRUE;
        break;
    }
  }

}

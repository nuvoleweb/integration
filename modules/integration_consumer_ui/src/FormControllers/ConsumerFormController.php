<?php

/**
 * @file
 * Contains ConsumerFormController.
 */

namespace Drupal\integration_consumer_ui\FormControllers;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration_ui\AbstractForm;
use Drupal\integration_consumer\Configuration\ConsumerConfiguration;
use Drupal\integration_ui\FormFactory;
use Drupal\integration_ui\FormHelper;

/**
 * Class ConsumerFormController.
 *
 * @package Drupal\integration_consumer_ui\FormControllers
 */
class ConsumerFormController extends AbstractForm {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    /** @var ConsumerConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);
    $plugin_manager = $this->getPluginManager($form_state);
    $form_factory = FormFactory::getInstance('consumer');

    // Add plugin type selection.
    $form['plugin_container'] = FormHelper::inlineFieldset(
      t('Consumer plugin')
    );
    $form['plugin_container']['plugin'] = FormHelper::hiddenLabelSelect(
      t('Consumer plugin'),
      FormHelper::asOptions($plugin_manager->getPluginDefinitions()),
      $configuration->getPlugin()
    );
    $form['plugin_container']['select_plugin'] = FormHelper::stepSubmit(
      t('Select plugin'),
      'select_plugin'
    );

    // Select entity bundle based on producer plugin type.
    if ($plugin = $configuration->getPlugin()) {

      $entity_type = $plugin_manager->getPlugin($plugin)->getEntityType();
      $entity_info = entity_get_info($entity_type);

      $form['entity_bundle_container'] = FormHelper::inlineFieldset(t('Entity bundle'));
      $form['entity_bundle_container']['entity_bundle'] = FormHelper::hiddenLabelSelect(
        t('Entity bundle'),
        FormHelper::asOptions($entity_info['bundles']),
        $configuration->getEntityBundle()
      );
      $form['entity_bundle_container']['entity_bundle_submit'] = FormHelper::stepSubmit(
        t('Select bundle'),
        'entity_bundle_submit'
      );
    }

    // Add resource schema form portion.
    if ($entity_bundle = $configuration->getEntityBundle()) {

      $form['resource_container'] = FormHelper::inlineFieldset(
        t('Resource schema')
      );
      $form['resource_container']['resource'] = FormHelper::hiddenLabelSelect(
        t('Resource schema'),
        $this->getResourceSchemasAsOptions(),
        (array) $configuration->getPluginSetting('resource_schema')
      );
      $form['resource_container']['resource_submit'] = FormHelper::stepSubmit(
        t('Select resource schema'),
        'resource_submit'
      );
    }
    $form['settings'] = FormHelper::tree();
    $form['settings']['plugin'] = FormHelper::tree(FALSE);

    // Add field mapping form portion.
    $resource_name = $configuration->getResourceSchema();
    if ($plugin && $resource_name) {

      // @todo: change this by setting proper getters on entity property info.
      $resource = ConfigurationFactory::load('integration_resource_schema', $resource_name);
      $source_options = array('' => '') + (array) $resource->getPluginSetting('fields');
      $destination_options = $this->getDestinationOptions($entity_type, $entity_bundle);

      $rows = array();
      $mapping = (array) $configuration->getPluginSetting('mapping');
      foreach ($mapping as $source => $destination) {
        $form['settings']['plugin']['mapping'][$source] = FormHelper::hidden($destination);

        $row = array();
        $row['source'] = FormHelper::markup($source_options[$source]);
        $row['destination'] = FormHelper::markup($destination_options[$destination]);
        $row['remove_mapping'] = FormHelper::stepSubmit(t('Remove'), 'remove_mapping');
        $row['remove_mapping']['#field'] = $source;
        $rows[] = $row;
      }

      $rows[] = array(
        'source' => FormHelper::select(NULL, $source_options),
        'destination' => FormHelper::select(NULL, $destination_options),
        'add_field_mapping' => FormHelper::stepSubmit(t('Add mapping'), 'add_field_mapping'),
      );

      $header = array(t('Source'), t('Destination'), '');
      $form['mapping'] = FormHelper::table($header, $rows);
    }
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
    /** @var ConsumerConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);
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

  /**
   * Get options list of possible entity type destinations.
   *
   * @param string $entity_type
   *    Entity type machine name.
   *
   * @return array
   *    List of entity type fields and properties.
   */
  protected function getDestinationOptions($entity_type, $entity_bundle) {
    $options = array('' => '');

    /** @var \EntityDrupalWrapper $entity_wrapper */
    $entity_wrapper = entity_metadata_wrapper($entity_type);
    $properties = $entity_wrapper->refPropertyInfo();
    foreach ($properties['properties'] as $key => $value) {
      $options[$key] = t('Property: @label (@machine_name)', array('@label' => $value['label'], '@machine_name' => $key));
    }
    foreach ($properties['bundles'][$entity_bundle]['properties'] as $key => $value) {
      $options[$key] = t('Field: @label (@machine_name)', array('@label' => $value['label'], '@machine_name' => $key));
    }
    asort($options);
    return $options;
  }

}

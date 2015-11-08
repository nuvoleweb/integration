<?php

/**
 * @file
 * Contains ProducerFormController.
 */

namespace Drupal\integration_producer_ui\FormControllers;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration_producer\Configuration\ProducerConfiguration;
use Drupal\integration_ui\AbstractForm;
use Drupal\integration_ui\FormHelper;

/**
 * Class ProducerFormController.
 *
 * @package Drupal\integration_producer_ui\FormControllers
 */
class ProducerFormController extends AbstractForm {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    /** @var ProducerConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);
    $plugin_manager = $this->getPluginManager($form_state);

    // Add plugin type selection.
    $form += FormHelper::choosePlugin(t('Producer plugin'), $configuration, $plugin_manager);

    // Select entity bundle based on producer plugin type.
    if ($plugin = $configuration->getPlugin()) {
      $form += FormHelper::chooseEntityBundle($plugin, $configuration, $plugin_manager);
    }

    // Add resource schema form portion.
    if ($entity_bundle = $configuration->getEntityBundle()) {
      $options = $this->getResourceSchemasAsOptions();
      $default_value = (array) $configuration->getPluginSetting('resource_schema');

      $form['resource_container'] = FormHelper::inlineFieldset(t('Resource schema'));
      $form['resource_container']['resource'] = FormHelper::hiddenLabelSelect(t('Resource schema'), $options, $default_value);
      $form['resource_container']['resource_submit'] = FormHelper::stepSubmit(t('Select resource schema'), 'resource_submit');
    }
    $form['settings'] = FormHelper::tree();
    $form['settings']['plugin'] = FormHelper::tree(FALSE);

    // Add field mapping form portion.
    $resource_name = $configuration->getResourceSchema();
    if ($plugin && $resource_name) {
      $entity_type = $plugin_manager->getPlugin($plugin)->getEntityType();

      $source_options = $this->getEntityFieldList($entity_type, $entity_bundle);
      // @todo: change this by setting proper getters on entity property info.
      $resource = ConfigurationFactory::load('integration_resource_schema', $resource_name);
      $destination_options = ['' => ''] + (array) $resource->getPluginSetting('fields');

      $rows = [];
      $mapping = (array) $configuration->getPluginSetting('mapping');
      foreach ($mapping as $source => $destination) {
        $form['settings']['plugin']['mapping'][$source] = FormHelper::hidden($destination);

        $row = [
          'source' => FormHelper::markup($source_options[$source]),
          'destination' => FormHelper::markup($destination_options[$destination]),
          'remove_mapping' => FormHelper::stepSubmit(t('Remove'), 'remove_mapping'),
        ];
        $rows[] = $row;
      }

      $rows[] = [
        'source' => FormHelper::select(NULL, $source_options),
        'destination' => FormHelper::select(NULL, $destination_options),
        'add_field_mapping' => FormHelper::stepSubmit(t('Add mapping'), 'add_field_mapping'),
      ];

      $header = [t('Source'), t('Destination'), NULL];
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
    /** @var ProducerConfiguration $configuration */
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

}

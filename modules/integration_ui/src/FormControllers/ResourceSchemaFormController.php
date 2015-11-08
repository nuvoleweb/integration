<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormControllers\ResourceSchemaFormController.
 */

namespace Drupal\integration_ui\FormControllers;

use Drupal\integration\ResourceSchema\Configuration\ResourceSchemaConfiguration;
use Drupal\integration_ui\AbstractForm;
use Drupal\integration_ui\FormHelper;

/**
 * Class ResourceSchemaFormController.
 *
 * @package Drupal\integration_ui\FormControllers
 */
class ResourceSchemaFormController extends AbstractForm {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    /** @var ResourceSchemaConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);
    $plugin_manager = $this->getPluginManager($form_state);

    // Add plugin type selection.
    $form += FormHelper::choosePlugin(t('Resource schema plugin'), $configuration, $plugin_manager);

    $form['settings'] = FormHelper::tree();
    $form['settings']['plugin'] = FormHelper::tree(FALSE);

    $i = 0;
    $rows = [];
    $fields = (array) $configuration->getPluginSetting('fields');
    foreach ($fields as $name => $label) {
      $form['settings']['plugin']['fields'][$name] = FormHelper::hidden($label);

      $row = [
        'name' => FormHelper::markup($name),
        'label' => FormHelper::markup($label),
        "remove_field_$i" => FormHelper::stepSubmit(t('Remove'), $name),
      ];
      $rows[] = $row;
      $i++;
    }

    $rows[] = [
      'field_name' => FormHelper::textField(NULL, NULL, FALSE),
      'field_label' => FormHelper::textField(NULL, NULL, FALSE),
      'add_field' => FormHelper::stepSubmit(t('Add'), 'add_field'),
    ];

    $header = [t('Field name'), t('Field label'), NULL];
    $form['settings']['fields'] = FormHelper::table($header, $rows);
  }

  /**
   * {@inheritdoc}
   */
  public function formSubmit(array $form, array &$form_state) {
    /** @var ResourceSchemaConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);
    $input = &$form_state['input'];
    $triggering_element = $form_state['triggering_element'];

    switch ($triggering_element['#name']) {

      case 'select_plugin':
        $form_state['rebuild'] = TRUE;
        break;

      // Add field to plugin settings.
      case 'add_field':
        if ($input['field_name'] && $input['field_label']) {
          $configuration->settings['plugin']['fields'][$input['field_name']] = $input['field_label'];
        }
        $input['field_name'] = $input['field_label'] = '';
        $form_state['rebuild'] = TRUE;
        break;

      // Remove field from plugin settings.
      case 'remove_field':
        $field_name = $triggering_element['#field'];
        unset($configuration->settings['plugin']['fields'][$field_name]);
        $form_state['rebuild'] = TRUE;
        break;
    }

    // Remove UI-related values from plugin settings.
    foreach (['fields'] as $name) {
      unset($configuration->settings[$name]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, array &$form_state) {

  }

}

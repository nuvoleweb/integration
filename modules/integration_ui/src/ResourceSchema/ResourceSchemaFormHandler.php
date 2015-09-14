<?php

/**
 * @file
 * Contains \Drupal\integration_ui\ResourceSchema\ResourceSchemaFormHandler.
 */

namespace Drupal\integration_ui\ResourceSchema;

use Drupal\integration_ui\AbstractFormHandler;
use Drupal\integration\ResourceSchema\Configuration\ResourceSchemaConfiguration;

/**
 * Class ResourceSchemaFormHandler.
 *
 * @package Drupal\integration_ui\ResourceSchema
 */
class ResourceSchemaFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    /** @var ResourceSchemaConfiguration $configuration */
    $configuration = $this->getConfiguration();

    $options = $this->getPluginManager()->getFormOptions();
    $form['plugin'] = array(
      '#type' => 'radios',
      '#title' => t('Resource schema plugin'),
      '#options' => $options,
      '#default_value' => $configuration->getPlugin(),
      '#required' => TRUE,
    );
    foreach ($options as $name => $label) {
      $form['plugin'][$name] = array('#description' => $this->getPluginManager()->getDescription($name));
    }

    $form['settings'] = array(
      '#tree' => TRUE,
    );
    $rows = array();
    $form['settings']['plugin'] = array(
      '#tree' => FALSE,
    );
    foreach ($configuration->getPluginSettings() as $name => $label) {
      $form['settings']['plugin'][$name] = array(
        '#value' => $label,
      );
      $row = array();
      $row['name'] = array('#markup' => $name);
      $row['label'] = array('#markup' => $label);
      $row['action'] = array(
        '#type' => 'submit',
        '#value' => t('Remove'),
        '#name' => 'remove-field',
        '#field' => $name,
        '#limit_validation_errors' => array(),
        '#submit' => array('integration_ui_entity_form_submit'),
      );
      $rows[] = $row;
    }
    $rows[] = array(
      'field_name' => array(
        '#type' => 'textfield',
        '#default_value' => '',
      ),
      'field_label' => array(
        '#type' => 'textfield',
        '#default_value' => '',
      ),
      'field_add' => array(
        '#type' => 'submit',
        '#value' => t('Add'),
        '#name' => 'add-field',
        '#limit_validation_errors' => array(),
        '#submit' => array('integration_ui_entity_form_submit'),
      ),
    );

    $header = array(t('Field name'), t('Field label'), '');
    $form['settings']['fields'] = array(
      '#theme' => 'integration_form_table',
      '#header' => $header,
      '#tree' => FALSE,
      'rows' => $rows,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formSubmit(array $form, array &$form_state) {
    /** @var ResourceSchemaConfiguration $configuration */
    $configuration = $this->getConfiguration();
    $input = &$form_state['input'];
    $triggering_element = $form_state['triggering_element'];

    switch ($triggering_element['#name']) {

      // Add field to plugin settings.
      case 'add-field':
        if ($input['field_name'] && $input['field_label']) {
          $configuration->setPluginSetting($input['field_name'], $input['field_label']);
        }
        $input['field_name'] = $input['field_label'] = '';
        $form_state['rebuild'] = TRUE;
        break;

      // Remove field from plugin settings.
      case 'remove-field':
        $configuration->unsetPluginSetting($triggering_element['#field']);
        $form_state['rebuild'] = TRUE;
        break;
    }

    // Remove UI-related values from plugin settings.
    foreach (array('fields') as $name) {
      unset($configuration->settings[$name]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, array &$form_state) {

  }

}

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
   *
   */
  public function form(array &$form, array &$form_state, $op) {
    /** @var ResourceSchemaConfiguration $configuration */
    $configuration = $this->getConfiguration();

    $options = $this->getPluginManager()->getFormOptions();
    $form['plugin'] = array(
      '#type' => 'radios',
      '#title' => t('Resource schema plugin'),
      '#options' => $options,
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
      $row[] = array('#markup' => $name);
      $row[] = array('#markup' => $label);
      $row[] = array(
        '#type' => 'submit',
        '#value' => t('Remove'),
        '#name' => 'remove-field',
        '#field' => $name,
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
    $values = &$form_state['values'];
    $triggering_element = $form_state['triggering_element'];

    switch ($triggering_element['#name']) {

      // Add field to plugin settings.
      case 'add-field':
        if ($values['field_name'] && $values['field_label']) {
          $configuration->setPluginSetting($values['field_name'], $values['field_label']);
        }
        $values['field_name'] = $values['field_label'] = '';
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

    $form_state['rebuild'] = TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, array &$form_state) {

  }

}

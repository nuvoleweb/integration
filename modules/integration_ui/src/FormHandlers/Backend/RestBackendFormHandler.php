<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHandlers\Backend\RestBackendFormHandler.
 */

namespace Drupal\integration_ui\FormHandlers\Backend;

use Drupal\integration_ui\FormHelper;

/**
 * Class RestBackendFormHandler.
 *
 * @package Drupal\integration_ui\FormHandlers\Backend
 */
class RestBackendFormHandler extends AbstractBackendFormHandler {

  /**
   * {@inheritdoc}
   */
  public function resourceSchemaForm($machine_name, array &$form, array &$form_state, $op) {
    $configuration = $this->getConfiguration($form_state);

    $form['endpoint'] = FormHelper::textField(
      t('Endpoint'),
      $configuration->getPluginSetting('endpoint')
    );
    $form['changes'] = FormHelper::textField(
      t('Change feed'),
      $configuration->getPluginSetting('changes')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    $configuration = $this->getConfiguration($form_state);

    $form['base'] = FormHelper::textField(
      t('Base URL'),
      $configuration->getPluginSetting('base')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formSubmit(array $form, array &$form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, array &$form_state) {

  }

}

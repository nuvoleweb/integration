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
class FileSystemBackendFormHandler extends AbstractBackendFormHandler {

  /**
   * {@inheritdoc}
   */
  public function resourceSchemaForm($machine_name, array &$form, array &$form_state, $op) {
    $configuration = $this->getConfiguration($form_state);
    $default_value = $configuration->getPluginSetting("resource_schema.$machine_name.folder");
    $form['folder'] = FormHelper::textField(t('Folder'), $default_value);
  }

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    $configuration = $this->getConfiguration($form_state);
    $default_value = $configuration->getPluginSetting('backend.path');
    $form['path'] = FormHelper::textField(t('File system path'), $default_value);
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

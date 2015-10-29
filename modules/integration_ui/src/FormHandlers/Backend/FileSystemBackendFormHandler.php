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
  public function resourceSchemaForm(array &$form, array &$form_state, $op) {
    $configuration = $this->getConfiguration($form_state);

    $form['folder'] = FormHelper::textField(t('Folder'), $configuration->getPluginSetting('folder'));
  }

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {

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

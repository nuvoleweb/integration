<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHandlers\Backend\RestBackendFormHandler.
 */

namespace Drupal\integration_ui\FormHandlers\Backend;

use Drupal\integration_ui\AbstractForm;
use Drupal\integration_ui\FormHelper;

/**
 * Class RestBackendFormHandler.
 *
 * @package Drupal\integration_ui\FormHandlers\Backend
 */
class RestBackendFormHandler extends AbstractForm {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
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
  public function formSubmit(array $form, array &$form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, array &$form_state) {

  }

}

<?php

/**
 * @file
 * Contains HttpAuthenticationFormHandler.
 */

namespace Drupal\integration_ui\Backend\Authentication;

use Drupal\integration_ui\AbstractFormHandler;

/**
 * Class HttpAuthenticationFormHandler.
 *
 * @package Drupal\integration_ui\Backend\Authentication
 */
class HttpAuthenticationFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    $form['username'] = array(
      '#title' => t('Username'),
      '#type' => 'textfield',
      '#default_value' => $this->getConfiguration()->getComponentSetting('authentication_handler', 'username'),
      '#required' => TRUE,
    );
    $form['password'] = array(
      '#title' => t('Password'),
      '#type' => 'textfield',
      '#default_value' => $this->getConfiguration()->getComponentSetting('authentication_handler', 'password'),
      '#required' => TRUE,
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

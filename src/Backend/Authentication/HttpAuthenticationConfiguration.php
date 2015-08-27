<?php

/**
 * @file
 * Contains HttpAuthenticationConfiguration.
 */

namespace Drupal\integration\Backend\Authentication;

use Drupal\integration\Configuration\AbstractComponentConfiguration;
use Drupal\integration\Configuration\FormInterface;

/**
 * Class HttpAuthenticationConfiguration.
 *
 * @package Drupal\integration\Backend\Authentication
 */
class HttpAuthenticationConfiguration extends AbstractComponentConfiguration implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    $form['username'] = array(
      '#title' => t('Username'),
      '#type' => 'textfield',
      '#default_value' => $this->getConfiguration()->getOption('username'),
      '#required' => TRUE,
    );
    $form['password'] = array(
      '#title' => t('Password'),
      '#type' => 'textfield',
      '#default_value' => $this->getConfiguration()->getOption('password'),
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

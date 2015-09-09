<?php

/**
 * @file
 * Contains \Drupal\integration_ui\Backend\BackendFormHandler.
 */

namespace Drupal\integration_ui\Backend;

use Drupal\integration_ui\AbstractFormHandler;

/**
 * Class BackendFormHandler.
 *
 * @package Drupal\integration_ui\Backend
 */
class BackendFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    $form['type'] = $this->getFormRadios(t('Backend type'), '', TRUE);
    $this->componentsForm($form, $form_state, $op);
  }

}

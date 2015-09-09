<?php

/**
 * @file
 * Contains \Drupal\integration_producer_ui\ProducerFormHandler.
 */

namespace Drupal\integration_producer_ui;

use Drupal\integration_ui\AbstractFormHandler;

/**
 * Class ProducerFormHandler.
 *
 * @package Drupal\integration_producer_ui
 */
class ProducerFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    $form['type'] = $this->getFormRadios(t('Producer type'), '', TRUE);
    $this->componentsForm($form, $form_state, $op);
  }

}

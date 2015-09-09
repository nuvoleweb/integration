<?php

/**
 * @file
 * Contains \Drupal\integration_ui\Producer\ProducerFormHandler.
 */

namespace Drupal\integration_ui\Producer;

use Drupal\integration_ui\AbstractFormHandler;

/**
 * Class ProducerFormHandler.
 *
 * @package Drupal\integration_ui\Producer
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

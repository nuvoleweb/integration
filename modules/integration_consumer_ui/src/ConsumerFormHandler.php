<?php

/**
 * @file
 * Contains \Drupal\integration_consumer_ui\ConsumerFormHandler.
 */

namespace Drupal\integration_consumer_ui;

use Drupal\integration_ui\AbstractFormHandler;

/**
 * Class ConsumerFormHandler.
 *
 * @package Drupal\integration_consumer_ui
 */
class ConsumerFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    $this->componentsForm($form, $form_state, $op);
  }

}

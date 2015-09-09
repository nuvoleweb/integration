<?php

/**
 * @file
 * Contains \Drupal\integration_ui\Consumer\ConsumerFormHandler.
 */

namespace Drupal\integration_ui\Consumer;

use Drupal\integration_ui\AbstractFormHandler;

/**
 * Class ConsumerFormHandler.
 *
 * @package Drupal\integration_ui\Consumer
 */
class ConsumerFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    $this->componentsForm($form, $form_state, $op);
  }

}

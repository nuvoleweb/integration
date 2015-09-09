<?php

/**
 * @file
 * Contains FormHandlerInterface.
 */

namespace Drupal\integration_ui;

use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Interface FormHandlerInterface.
 *
 * @package Drupal\integration_ui
 */
interface FormHandlerInterface {

  /**
   * Build form array.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   * @param string $op
   *    Current form operation.
   */
  public function form(array &$form, array &$form_state, $op);

  /**
   * Handle form submission.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   */
  public function formSubmit(array $form, array &$form_state);

  /**
   * Handle form validation.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   */
  public function formValidate(array $form, array &$form_state);

}

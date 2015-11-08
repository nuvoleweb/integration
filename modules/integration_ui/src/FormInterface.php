<?php

/**
 * @file
 * Contains Drupal\integration_ui\FormHandlers\FormInterface.
 */

namespace Drupal\integration_ui;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Plugins\PluginManager;

/**
 * Interface FormInterface.
 *
 * @package Drupal\integration_ui
 */
interface FormInterface {

  /**
   * Extract configuration entity from current $form_state array.
   *
   * @param array $form_state
   *    Form state array.
   *
   * @return AbstractConfiguration
   *    Current configuration entity object.
   */
  public function getConfiguration(array &$form_state);

  /**
   * Return plugin manager instance from current $form_state array.
   *
   * @return PluginManager
   *    Plugin manager instance.
   */
  public function getPluginManager(array &$form_state);

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

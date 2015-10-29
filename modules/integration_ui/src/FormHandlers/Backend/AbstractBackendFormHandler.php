<?php

/**
 * @file
 * Contains AbstractBackendFormHandler.
 */

namespace Drupal\integration_ui\FormHandlers\Backend;

use Drupal\integration_ui\AbstractForm;

/**
 * Class AbstractBackendFormHandler.
 *
 * @package Drupal\integration_ui\FormHandlers
 */
abstract class AbstractBackendFormHandler extends AbstractForm {

  /**
   * Build resource schema specific form array.
   *
   * @param array $form
   *    Form array.
   * @param array $form_state
   *    Form state array.
   * @param string $op
   *    Current form operation.
   */
  abstract public function resourceSchemaForm(array &$form, array &$form_state, $op);

}

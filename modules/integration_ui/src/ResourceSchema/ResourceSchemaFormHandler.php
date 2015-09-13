<?php

/**
 * @file
 * Contains \Drupal\integration_ui\ResourceSchema\ResourceSchemaFormHandler.
 */

namespace Drupal\integration_ui\ResourceSchema;

use Drupal\integration_ui\AbstractFormHandler;

/**
 * Class ResourceSchemaFormHandler.
 * 
 * @package Drupal\integration_ui\ResourceSchema
 */
class ResourceSchemaFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    $form['plugin'] = $this->getFormRadios(t('Resource schema plugin'), '', TRUE);
    $this->componentsForm($form, $form_state, $op);
  }

}

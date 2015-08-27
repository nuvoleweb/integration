<?php

/**
 * @file
 * Contains administrative forms.
 */

use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Generates the profile type editing form.
 */
function integration_ui_entity_form($form, &$form_state, AbstractConfiguration $configuration, $op = 'edit') {

  $configuration->form($form, $form_state, $op);

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 40,
  );
  return $form;
}

/**
 * Form API submit callback for the type form.
 *
 * @see integration_backend_form()
 */
function integration_backend_form_submit(&$form, &$form_state) {
  /** @var AbstractConfiguration $configuration */
  $configuration = entity_ui_form_submit_build_entity($form, $form_state);
  $configuration->save();

  drupal_set_message(t('Configuration has been saved.'));
  $form_state['redirect'] = 'admin/config/services/integration/backend';
}

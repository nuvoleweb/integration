<?php

/**
 * @file
 * Contains administrative forms.
 */

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration_ui\FormHandlerFactory;

/**
 * Generates the profile type editing form.
 */
function integration_ui_entity_form($form, &$form_state, AbstractConfiguration $configuration, $op = 'edit') {

  $form['name'] = array(
    '#title' => t('Name'),
    '#type' => 'textfield',
    '#default_value' => ($op == 'clone') ? $configuration->getName() . ' clone' : $configuration->getName(),
    '#required' => TRUE,
  );
  $form['machine_name'] = array(
    '#type' => 'machine_name',
    '#default_value' => $configuration->getMachineName(),
    '#disabled' => $configuration->getEnabled(),
    '#machine_name' => array(
      'exists' => 'integration_load_backend',
      'source' => array('name'),
    ),
    '#descripwtion' => t('A unique machine-readable name for this configuration object. It must only contain lowercase letters, numbers, and underscores.'),
    '#required' => TRUE,
  );
  $form['enabled'] = array(
    '#title' => t('Enabled'),
    '#type' => 'checkbox',
    '#default_value' => ($op == 'add') ? TRUE : $configuration->getEnabled(),
  );
  $form['status'] = array(
    '#value' => ($op == 'add') ? ENTITY_CUSTOM : $configuration->getStatus(),
  );

  $form_manager = FormHandlerFactory::getInstance($configuration);
  $form_manager->form($form, $form_state, $op);

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

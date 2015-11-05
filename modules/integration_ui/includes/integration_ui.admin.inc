<?php

/**
 * @file
 * Contains administrative forms.
 */

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration_ui\FormFactory;

/**
 * A wrapper around drupal_get_form() that helps building entity forms.
 *
 * @param string $entity_type
 *   The entity type for which to get the form.
 * @param object $entity
 *   The entity for which to return the form.
 * @param string $op
 *   One of 'edit', 'add' or 'clone'. Defaults to edit.
 * @param array $form_state
 *   A pre-populated form state.
 *
 * @return array
 *   The fully built and processed form, ready to be rendered.
 *
 * @see entity_ui_get_form()
 */
function integration_ui_get_form($entity_type, $entity, $op = 'edit', array $form_state = []) {
  if (!isset($entity) && $op == 'add') {
    $entity = entity_create($entity_type, []);
  }

  $form_state['wrapper_callback'] = 'entity_ui_main_form_defaults';
  $form_state['entity_type'] = $entity_type;
  form_load_include($form_state, 'inc', 'entity', 'includes/entity.ui');

  if ($op == 'clone') {
    $entity = entity_ui_clone_entity($entity_type, $entity);
  }

  $form_state['build_info']['args'] = [$entity, $op, $entity_type];
  return drupal_build_form('integration_ui_entity_form', $form_state);
}

/**
 * Implements hook_action_info().
 */
function integration_ui_form_ajax_callback($form, $form_state) {
  $target = $form_state['triggering_element']['#ajax']['target'];
  return $form[$target];
}

/**
 * Generates configuration entity form.
 */
function integration_ui_entity_form($form, &$form_state, AbstractConfiguration $configuration, $op, $entity_type) {

  $form['name'] = [
    '#title' => t('Name'),
    '#type' => 'textfield',
    '#default_value' => ($op == 'clone') ? $configuration->getName() . ' clone' : $configuration->getName(),
    '#required' => TRUE,
  ];
  $form['machine_name'] = [
    '#type' => 'machine_name',
    '#default_value' => $configuration->getMachineName(),
    '#machine_name' => [
      'exists' => 'integration_ui_machine_name_exists',
      'source' => ['name'],
    ],
    '#descripwtion' => t('A unique machine-readable name for this configuration object. It must only contain lowercase letters, numbers, and underscores.'),
    '#required' => TRUE,
    '#entity_type' => $entity_type,
  ];
  $form['enabled'] = [
    '#title' => t('Enabled'),
    '#type' => 'checkbox',
    '#default_value' => ($op == 'add') ? TRUE : $configuration->getEnabled(),
  ];
  $form['status'] = [
    '#value' => ($op == 'add') ? ENTITY_CUSTOM : $configuration->getStatus(),
  ];

  $form_manager = FormFactory::getInstance($entity_type);
  $form_manager->getController()->form($form, $form_state, $op);

  $form['actions'] = ['#type' => 'actions'];
  $form['actions']['submit'] = [
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 40,
  ];
  return $form;
}

/**
 * Form API submit callback.
 *
 * @see integration_backend_form()
 */
function integration_ui_entity_form_submit(&$form, &$form_state) {
  /** @var AbstractConfiguration $configuration */
  $configuration = entity_ui_form_submit_build_entity($form, $form_state);

  $form_manager = FormFactory::getInstance($configuration->entityType());
  $form_manager->getController()->formSubmit($form, $form_state);

  if (isset($form_state['rebuild']) && !$form_state['rebuild']) {
    $configuration->save();
    drupal_set_message(t('Configuration has been saved.'));
    $form_state['redirect'] = 'admin/config/integration/' . arg(3);
  }
}

/**
 * Form API validate callback.
 *
 * @see integration_backend_form()
 */
function integration_ui_entity_form_validate(&$form, &$form_state) {
  /** @var AbstractConfiguration $configuration */
  $configuration = entity_ui_form_submit_build_entity($form, $form_state);

  $entity_type = $configuration->entityType();
  $machine_name = $configuration->getMachineName();

  if (!$machine_name) {
    form_set_error('machine_name', t('Machine name cannot be empty.'));
  }
  elseif ($form_state['op'] == 'create' && entity_load_single($entity_type, $machine_name)) {
    form_set_error('machine_name', t('A configuration with the same machine name already exists.'));
  }
}

/**
 * Checks for machine name uniqueness.
 *
 * @param string $machine_name
 *    Current machine name value.
 * @param string $element
 *    Machine name form element.
 * @param array $form_state
 *    Form state array.
 *
 * @return bool
 *    TRUE if machine name exists, FALSE otherwise.
 *
 * @see integration_ui_entity_form()
 */
function integration_ui_machine_name_exists($machine_name, $element, array $form_state) {
  return (bool) entity_load_single($element['#entity_type'], $machine_name);
}

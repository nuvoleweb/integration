<?php

/**
 * @file
 * Contains \Drupal\integration_producer_ui\ProducerFormHandler.
 */

namespace Drupal\integration_producer_ui;

use Drupal\integration_ui\AbstractFormHandler;
use Drupal\integration\Backend\Configuration\BackendConfiguration;

/**
 * Class ProducerFormHandler.
 *
 * @package Drupal\integration_producer_ui
 */
class ProducerFormHandler extends AbstractFormHandler {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    /** @var BackendConfiguration $configuration */
    $configuration = $this->getConfiguration();

    $options = $this->getPluginManager()->getFormOptions();
    $form['plugin'] = $this->formSelect(t('Producer plugin'), $options);

    // If ($entity_type = $this->getFormValue($form_state, 'type')) {
    //      $info = entity_get_info($entity_type);
    //      $options = $this->extractSelectOptions($info['bundles'], 'label');
    //      $form['bundle'] = $this->formSelect(t('Bundle'), $options);
    //    }
    //
    //    if ($bundle = $this->getFormValue($form_state, 'bundle')) {
    //
    //      $fields = field_info_instances($entity_type, $bundle);
    //      $options = $this->extractSelectOptions($fields, 'label');
    //
    //      $rows = array();
    //      $row = array(
    //        'source' => $this->formSelect('', $options),
    //        'destination' => $this->formTextField(''),
    //        'action' => array(
    //          '#type' => 'button',
    //          '#value' => t('Remove'),
    //        ),
    //      );
    //      $rows[] = $row;
    //
    //      $header = array(t('Source'), t('Destination'), '');
    //      $form['mapping'] = $this->formTable($header, $rows);
    //      $form['mapping']['more'] = array(
    //        '#type' => 'button',
    //        '#value' => t('Add more'),
    //      );
    //    }
    //
    //    // Set AJAX dependencies.
    //    $this->setAjaxDependency($form, 'type', 'bundle');
    //    $this->setAjaxDependency($form, 'bundle', 'mapping');
  }

}

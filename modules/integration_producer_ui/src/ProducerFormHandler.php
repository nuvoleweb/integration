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
    $form['type'] = $this->getSelect(t('Type'), $options);

    if ($entity_type = $this->getFormValue($form_state, 'type')) {
      $form['options'] = $this->getFieldset(t('Options'));

      $info = entity_get_info($this->getFormValue($form_state, 'type'));
      $options = $this->extractSelectOptions($info['bundles'], 'label');
      $form['options']['bundle'] = $this->getSelect(t('Bundle'), $options);
    }

    if ($bundle = $this->getFormValue($form_state, 'bundle')) {

      $rows = array();
      $fields = field_info_instances($entity_type, $bundle);
      foreach ($fields as $field_name => $field) {
        $row = array();
        $row[$field_name . '_source'] = $this->getTextField($field['label']);
        $row[$field_name . '_destination'] = $this->getTextField(t('Destination'));
        $rows[$field_name . '_row'] = $row;
      }

      $form['options']['fields'] = $this->getFormTable(array(t('Source'), t('Destination')), $rows);
    }
  }

}

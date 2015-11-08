<?php

/**
 * @file
 * Contains \Drupal\integration_ui\AbstractForm.
 */

namespace Drupal\integration_ui;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Plugins\PluginManager;

/**
 * Class AbstractForm.
 *
 * @package Drupal\integration_ui
 */
abstract class AbstractForm implements FormInterface {

  /**
   * Configuration entity we are building the form for.
   *
   * @var AbstractConfiguration
   */
  protected $configuration = NULL;

  /**
   * {@inheritdoc}
   */
  public function getConfiguration(array &$form_state) {
    // @todo throw exception if none is set.
    return $form_state['build_info']['args'][0];
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginManager(array &$form_state) {
    // @todo throw exception if none is set.
    $entity_type = $form_state['build_info']['args'][2];
    return PluginManager::getInstance($entity_type);
  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, array &$form_state) {
    /** @var AbstractConfiguration $configuration */
    $configuration = $this->getConfiguration($form_state);

    if (!$configuration->validate()) {
      foreach ($configuration->getErrors() as $key => $message) {
        form_set_error($key, $message);
      }
    }
  }

  /**
   * Load all available backends and format them as an #options array.
   *
   * @return array
   *    Form element options.
   */
  protected function getBackendsAsOptions() {
    $options = [];
    foreach (entity_load('integration_backend') as $backend) {
      /** @var AbstractConfiguration $backend */
      $options[$backend->getMachineName()] = $backend->getName();
    }
    return $options;
  }

  /**
   * Load all available resource schema and format them as an #options array.
   *
   * @return array
   *    Form element options.
   */
  protected function getResourceSchemasAsOptions() {
    $options = [];
    foreach (entity_load('integration_resource_schema') as $resource) {
      /** @var AbstractConfiguration $resource */
      $options[$resource->getMachineName()] = $resource->getName();
    }
    return $options;
  }

  /**
   * Get resource schema label given its machine name.
   *
   * @param string $resource
   *    Response schema machine name.
   *
   * @return string
   *    Response schema label.
   */
  protected function getResourceSchemaLabel($resource) {
    $resource_schema = ConfigurationFactory::load('integration_resource_schema', $resource);
    return $resource_schema->getName();
  }

  /**
   * Get list of ist of entity type fields and properties.
   *
   * @param string $entity_type
   *    Entity type machine name.
   *
   * @return array
   *    List of entity type fields and properties.
   */
  protected function getEntityFieldList($entity_type, $entity_bundle) {
    $options = ['' => ''];

    /** @var \EntityDrupalWrapper $entity_wrapper */
    $entity_wrapper = entity_metadata_wrapper($entity_type);
    $properties = $entity_wrapper->refPropertyInfo();
    foreach ($properties['properties'] as $key => $value) {
      $options[$key] = t('Property: @label (@machine_name)', ['@label' => $value['label'], '@machine_name' => $key]);
    }
    foreach ($properties['bundles'][$entity_bundle]['properties'] as $key => $value) {
      $options[$key] = t('Field: @label (@machine_name)', ['@label' => $value['label'], '@machine_name' => $key]);
    }
    asort($options);
    return $options;
  }

}

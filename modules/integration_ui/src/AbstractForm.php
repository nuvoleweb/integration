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
   * Load all available resource schema and format them as an #options array.
   *
   * @return array
   *    Form element options.
   */
  protected function getResourceSchemasAsOptions() {
    $options = array();
    $resources = entity_load('integration_resource_schema');
    foreach ($resources as $resource) {
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

}

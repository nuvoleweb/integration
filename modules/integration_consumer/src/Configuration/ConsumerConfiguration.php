<?php

/**
 * @file
 * Contains ConsumerConfiguration.
 */

namespace Drupal\integration_consumer\Configuration;

use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;

/**
 * Class ConsumerConfiguration.
 *
 * @package Drupal\integration_consumer
 */
class ConsumerConfiguration extends AbstractConfiguration {

  // @codingStandardsIgnoreStart
  /**
   * Entity bundle the current consumer is operating on.
   *
   * @var string
   */
  public $entity_bundle = NULL;
  // @codingStandardsIgnoreEnd

  /**
   * Backend configuration machine name.
   *
   * @var string
   */
  public $backend = NULL;

  /**
   * Resource configuration machine name.
   *
   * @var string
   */
  public $resource = NULL;

  /**
   * Contains consumer field mapping.
   *
   * @var array
   *    Array of consumer field mapping.
   */
  public $mapping = [];

  /**
   * Contains consumer options.
   *
   * @var array
   *    Array of consumer specific option.
   */
  public $settings = [];

  /**
   * Return backend configuration machine name.
   *
   * @return string
   *    Backend configuration machine name.
   */
  public function getBackend() {
    return $this->backend;
  }

  /**
   * Return wrapped backend configuration entity.
   *
   * @return BackendConfiguration
   *    Backend configuration entity.
   */
  public function getBackendConfiguration() {
    return ConfigurationFactory::load('integration_backend', $this->backend);
  }

  /**
   * Return consumer field mapping.
   *
   * @return array
   *    Field mapping.
   */
  public function getMapping() {
    return $this->getPluginSetting('mapping') ? $this->getPluginSetting('mapping') : [];
  }

  /**
   * Return destination field given source field.
   *
   * @param string $destination_field
   *    Destination field.
   *
   * @return null
   *    Source field mapped to the destination field if any, NULL otherwise.
   */
  public function getMappingSource($destination_field) {
    $mapping = $this->getMapping();
    return isset($mapping[$destination_field]) ? $mapping[$destination_field] : NULL;
  }

  /**
   * Return source field given destination field.
   *
   * @param string $source_field
   *    Source field.
   *
   * @return null
   *    Destination field mapped to the source field if any, NULL otherwise.
   */
  public function getMappingDestination($source_field) {
    $mapping = array_flip($this->getMapping());
    return isset($mapping[$source_field]) ? $mapping[$source_field] : NULL;
  }

  /**
   * Get consumer entity bundle.
   *
   * @return string
   *    Entity bundle.
   */
  public function getEntityBundle() {
    return isset($this->entity_bundle) ? $this->entity_bundle : '';
  }

  /**
   * Get resource schema configuration machine name.
   *
   * @return string
   *    Resource schema configuration machine name.
   */
  public function getResourceSchema() {
    return isset($this->resource) ? $this->resource : '';
  }

}

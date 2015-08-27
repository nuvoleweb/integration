<?php

/**
 * @file
 * Contains ConsumerConfiguration.
 */

namespace Drupal\integration\Consumer\Configuration;

use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;

/**
 * Class ConsumerConfiguration.
 *
 * @package Drupal\integration\Consumer
 */
class ConsumerConfiguration extends AbstractConfiguration {

  /**
   * Backend machine name associated to current consumer configuration.
   *
   * @var string
   *    Backend machine name.
   */
  public $backend = '';

  /**
   * Contains consumer field mapping.
   *
   * @var array
   *    Array of consumer field mapping.
   */
  public $mapping = array();

  /**
   * Contains consumer options.
   *
   * @var array
   *    Array of consumer specific option.
   */
  public $options = array();

  /**
   * Return consumer entity type.
   *
   * @return string
   *    Entity type machine name.
   */
  public function getEntityType() {
    return isset($this->entity_type) ? $this->entity_type : '';
  }

  /**
   * Return consumer entity bundle.
   *
   * @return string
   *    Entity bundle machine name.
   */
  public function getEntityBundle() {
    return isset($this->entity_bundle) ? $this->entity_bundle : '';
  }

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
    return $this->mapping;
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
    return isset($this->mapping[$destination_field]) ? $this->mapping[$destination_field] : NULL;
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
    $mapping = array_flip($this->mapping);
    return isset($mapping[$source_field]) ? $mapping[$source_field] : NULL;
  }

}

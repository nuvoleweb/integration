<?php

/**
 * @file
 * Contains ProducerConfiguration.
 */

namespace Drupal\integration_producer\Configuration;

use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Class ProducerConfiguration.
 *
 * @package Drupal\integration_producer
 */
class ProducerConfiguration extends AbstractConfiguration {

  // @codingStandardsIgnoreStart
  /**
   * Entity bundle the current producer is operating on.
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
   * Contains consumer options.
   *
   * @var array
   *    Array of backend specific option.
   */
  public $settings = [];

  /**
   * Get current site's producer ID.
   *
   * @return string
   *    Producer ID.
   */
  public function getProducerId() {
    return variable_get('integration_producer_id', '');
  }

  /**
   * Get producer entity bundle.
   *
   * @return string
   *    Entity bundle.
   */
  public function getEntityBundle() {
    return isset($this->entity_bundle) ? $this->entity_bundle : '';
  }

  /**
   * Set producer entity bundle.
   *
   * @param string $entity_bundle
   *    Entity bundle.
   */
  public function setEntityBundle($entity_bundle) {
    $this->entity_bundle = $entity_bundle;
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

  /**
   * Set resource schema bundle.
   *
   * @param string $resource_schema
   *    Resource schema machine name.
   */
  public function setResourceSchema($resource_schema) {
    $this->resource = $resource_schema;
  }

  /**
   * Get resource backend machine name.
   *
   * @return string $backend
   *    Backend machine name.
   */
  public function getBackend() {
    return $this->backend;
  }

  /**
   * Set resource backend machine name.
   *
   * @param string $backend
   *    Backend machine name.
   *
   * @return $this
   */
  public function setBackend($backend) {
    $this->backend = $backend;
  }

  /**
   * Set field mapping.
   *
   * @param string $source
   *    Source field machine name.
   * @param string $destination
   *    Destination field machine name.
   *
   * @return $this
   */
  public function setMapping($source, $destination) {
    $this->setPluginSetting("mapping.$source", $destination);
  }

}

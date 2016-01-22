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
   * Get resource schema configuration machine name.
   *
   * @return string
   *    Resource schema configuration machine name.
   */
  public function getResourceSchema() {
    return isset($this->resource) ? $this->resource : '';
  }

}

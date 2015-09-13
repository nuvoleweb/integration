<?php

/**
 * @file
 * Contains ProducerConfiguration.
 */

namespace Drupal\integration_producer\Configuration;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\PluginManager;

/**
 * Class ProducerConfiguration.
 *
 * @package Drupal\integration_producer
 */
class ProducerConfiguration extends AbstractConfiguration {

  /**
   * Contains consumer options.
   *
   * @var array
   *    Array of backend specific option.
   */
  public $settings = array();

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
   * Get producer entity type setting parameter.
   *
   * @return string
   *    Entity type.
   */
  public function getType() {
    return isset($this->type) ? $this->type : '';
  }

  /**
   * Get option value given its name.
   *
   * @param string $name
   *    Option name.
   *
   * @return string
   *    Option value.
   */
  public function getOptionValue($name) {
    return isset($this->settings[$name]) ? $this->settings[$name] : '';
  }

}

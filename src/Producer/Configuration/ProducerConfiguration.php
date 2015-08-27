<?php

/**
 * @file
 * Contains ProducerConfiguration.
 */

namespace Drupal\integration\Producer\Configuration;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\PluginManager;

/**
 * Class ProducerConfiguration.
 *
 * @package Drupal\integration\Producer
 */
class ProducerConfiguration extends AbstractConfiguration {

  /**
   * Contains consumer options.
   *
   * @var array
   *    Array of backend specific option.
   */
  public $options = array();

  /**
   * Get producer ID setting parameter.
   *
   * @return string
   *    Producer ID.
   */
  public function getProducerId() {
    return isset($this->producer_id) ? $this->producer_id : '';
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
    return isset($this->options[$name]) ? $this->options[$name] : '';
  }

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, array &$form_state, $op) {
    parent::form($form, $form_state, $op);
    $plugin = PluginManager::getInstance('producer');
    $this->componentsForm($plugin, $form, $form_state, $op);
  }

}

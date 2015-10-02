<?php

/**
 * @file
 * Contains AbstractDefinition.
 */

namespace Drupal\integration\Plugins;

/**
 * Class AbstractDefinition.
 *
 * @package Drupal\integration\Plugins
 */
abstract class AbstractDefinition {

  /**
   * Current plugin definition.
   *
   * @var array
   */
  protected $definition;

  /**
   * AbstractDefinition constructor.
   *
   * @param array $definition
   *    Plugin or component definitions.
   *
   * @see hook_integration_backend_info()
   * @see hook_integration_backend_components_info()
   * @see hook_integration_producer_info()
   * @see hook_integration_producer_components_info()
   * @see hook_integration_consumer_info()
   * @see hook_integration_consumer_components_info()
   * @see hook_integration_resource_schema_info()
   */
  public function __construct(array $definition) {
    $this->definition = $definition;
  }

  /**
   * Get plugin or component label.
   *
   * @return string
   *    Plugin or component label.
   */
  public function getLabel() {
    return $this->definition['label'];
  }

  /**
   * Get plugin or component description.
   *
   * @return string
   *    Plugin or component description.
   */
  public function getDescription() {
    return $this->definition['description'];
  }

  /**
   * Get plugin or component class.
   *
   * @return string
   *    Plugin or component class.
   */
  public function getClass() {
    return $this->definition['class'];
  }

  /**
   * Get plugin or component form handler.
   *
   * @return string
   *    Plugin or component form handler.
   */
  public function getFormHandler() {
    return isset($this->definition['form handler']) ? $this->definition['form handler'] : NULL;
  }

}

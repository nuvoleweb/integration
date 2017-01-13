<?php

/**
 * @file
 * Contains \Drupal\integration\Plugins\AbstractDefinition.
 */

namespace Drupal\integration\Plugins;

use Drupal\integration\Plugins\Exceptions\PluginManagerException;

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
   *
   * @throws PluginManagerException
   *    Throws PluginManagerException.
   */
  public function getClass() {
    if (!isset($this->definition['class']) || empty($this->definition['class'])) {
      throw new PluginManagerException(t('"!label" class is not set or empty', [
        '!label' => $this->getLabel(),
      ]));
    }
    elseif (!class_exists($this->definition['class'])) {
      throw new PluginManagerException(t('"!label" class !name does not exists', [
        '!label' => $this->getLabel(),
        '!name' => $this->definition['class'],
      ]));
    }
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

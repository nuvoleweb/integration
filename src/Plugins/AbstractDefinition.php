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
   */
  public function __construct(array $definition) {
    $this->definition = $definition;
  }

  /**
   * @return mixed
   */
  public function getLabel() {
    return $this->definition['label'];
  }

  /**
   * @return mixed
   */
  public function getDescription() {
    return $this->definition['description'];
  }

  /**
   * @return mixed
   */
  public function getClass() {
    return $this->definition['class'];
  }

  /**
   * @return mixed
   */
  public function getFormHandler() {
    return isset($this->definition['form handler']) ? $this->definition['form handler'] : NULL;
  }

}

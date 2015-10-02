<?php

/**
 * @file
 * Contains ComponentDefinition.
 */

namespace Drupal\integration\Plugins;

/**
 * Class ComponentDefinition.
 *
 * @package Drupal\integration\Plugins
 */
class ComponentDefinition extends AbstractDefinition {

  /**
   * Get component type.
   *
   * @return string
   *    Component type.
   */
  public function getType() {
    return $this->definition['type'];
  }

}

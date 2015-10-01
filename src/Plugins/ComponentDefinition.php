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
   * @return mixed
   */
  public function getType() {
    return $this->definition['type'];
  }
}

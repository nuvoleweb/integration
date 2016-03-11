<?php

/**
 * @file
 * Contains Drupal\integration\Plugins\PluginDefinition.
 */

namespace Drupal\integration\Plugins;

/**
 * Class PluginDefinition.
 *
 * @package Drupal\integration\Plugins
 */
class PluginDefinition extends AbstractDefinition {

  /**
   * Get plugin entity type.
   *
   * @return string
   *    Plugin entity type.
   */
  public function getEntityType() {
    return isset($this->definition['entity type']) ? $this->definition['entity type'] : NULL;
  }

}

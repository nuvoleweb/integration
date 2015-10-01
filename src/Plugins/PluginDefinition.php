<?php

/**
 * @file
 * Contains PluginDefinition.
 */

namespace Drupal\integration\Plugins;

/**
 * Class PluginDefinition.
 *
 * @package Drupal\integration\Plugins
 */
class PluginDefinition extends AbstractDefinition {

  /**
   * @return mixed
   */
  public function getEntityType() {
    return isset($this->definition['entity type']) ? $this->definition['entity type'] : NULL;
  }

}

<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHandlerFactory
 */

namespace Drupal\integration_ui;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\PluginManager;

/**
 * Class FormHandlerFactory.
 *
 * @package Drupal\integration_ui
 */
class FormHandlerFactory {

  /**
   * Instantiate and return a form object given its configuration entity.
   *
   * @param AbstractConfiguration $configuration
   *    Configuration entity object.
   * @param null $plugin_type
   * @param null $component
   */
  static public function getInstance(AbstractConfiguration $configuration, $plugin_type = NULL, $component = NULL) {
    $plugin = str_replace('integration_', '', $configuration->entityType());

    if ($plugin_type && $component) {
      $plugin_manager = PluginManager::getInstance($plugin_type);
      $plugin_manager->setComponent($component);
    }
    elseif ($plugin_type) {

    }


    return ;
  }

}

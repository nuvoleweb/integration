<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHandlerFactory.
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
   * @param string $component
   *    Component name.
   * @param string $type
   *    Component type name.
   *
   * @return AbstractFormHandler|FALSE
   *    Form handler object if any, FALSE otherwise.
   */
  static public function getInstance(AbstractConfiguration $configuration, $component = NULL, $type = NULL) {
    $plugin_manager = PluginManager::getInstance($configuration->entityType());

    $is_plugin = !$component && !$type;
    $class = $is_plugin ? $plugin_manager->getFormHandler() : $plugin_manager->setComponent($component)->getFormHandler($type);
    if ($class && class_exists($class)) {
      return new $class($configuration, $plugin_manager);
    }
    return FALSE;
  }

}

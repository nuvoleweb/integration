<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHandlerFactory.
 */

namespace Drupal\integration_ui;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\PluginManager;
use Drupal\integration_ui\AbstractFormHandler;

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
   *
   * @return AbstractFormHandler|FALSE
   *    Form handler object if any, FALSE otherwise.
   */
  static public function getInstance(AbstractConfiguration $configuration, $component = NULL) {
    $plugin_type = str_replace('integration_', '', $configuration->entityType());
    $plugin_manager = PluginManager::getInstance($plugin_type);

    if (!$component) {
      $definition = $plugin_manager->getPluginDefinition();
    }
    else {
      $definition = $plugin_manager->setComponent($component)->getInfo();
    }
    if (isset($definition['form handler']) && class_exists($definition['form handler'])) {
      return new $definition['form handler']($configuration, $plugin_manager);
    }
    return FALSE;
  }

}

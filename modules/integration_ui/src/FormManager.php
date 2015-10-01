<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHandlerFactory.
 */

namespace Drupal\integration_ui;

use Drupal\integration\Plugins\PluginManager;

/**
 * Class FormManager.
 *
 * @package Drupal\integration_ui
 */
class FormManager {

  /**
   * Main plugin form handlers.
   *
   * @see integration_ui_entity_form()
   *
   * @var array
   */
  protected $handlers = array();

  /**
   * Plugin manager instance.
   *
   * @var PluginManager
   */
  protected $pluginManager = NULL;

  /**
   * Get plugin manager instance.
   *
   * @param string $plugin_type
   *    Plugin type, as defined in self::$definitions array's keys.
   *
   * @return FormManager
   *    PluginManager instance for specified plugin type.
   */
  static public function getInstance($plugin_type) {
    return new self($plugin_type);
  }

  /**
   * FormManager constructor.
   *
   * @param string $plugin_type
   *    Either a plugin type or its configuration entity type name.
   */
  public function __construct($plugin_type) {
    $this->plugin = str_replace('integration_', '', $plugin_type);

    $this->handlers = module_invoke_all("integration_ui_form_handlers");
    drupal_alter("integration_ui_form_handlers", $this->handlers);

    $this->pluginManager = PluginManager::getInstance($this->plugin);
  }

  /**
   * Get form handler for current plugin type.
   *
   * @return FormHandlerInterface
   *    Form handler instance
   */
  public function getHandler() {
    // @todo Throw exception is not set.
    if (isset($this->handlers[$this->plugin])) {
      return new $this->handlers[$this->plugin]();
    }
  }

  /**
   * Get form handler for current plugin type.
   *
   * @param string $plugin
   *    Plugin name.
   *
   * @return FormHandlerInterface
   *    Form handler instance
   */
  public function getPluginHandler($plugin) {
    // @todo Throw exception is not set.
    $form_handler = $this->pluginManager->getPlugin($plugin)->getFormHandler();
    if ($form_handler) {
      return new $form_handler();
    }
  }

  /**
   * Get form handler for current plugin type.
   *
   * @param string $component
   *    Component name.
   *
   * @return FormHandlerInterface
   *    Form handler instance
   */
  public function getComponentHandler($component) {
    // @todo Throw exception is not set.
    $form_handler = $this->pluginManager->getComponent($component)->getFormHandler();
    if ($form_handler) {
      return new $form_handler();
    }
  }

}

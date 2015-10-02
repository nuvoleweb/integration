<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHandlerFactory.
 */

namespace Drupal\integration_ui;

use Drupal\integration\Plugins\PluginManager;

/**
 * Class FormFactory.
 *
 * @package Drupal\integration_ui
 */
class FormFactory {

  /**
   * List of form controller classes for main integration plugin types.
   *
   * @see integration_ui_entity_form()
   *
   * @var array
   */
  protected $controllers = array();

  /**
   * Plugin manager instance.
   *
   * @var PluginManager
   */
  protected $pluginManager = NULL;

  /**
   * Get plugin manager instance.
   *
   * @param string $plugin
   *    Plugin type, as defined in self::$definitions array's keys.
   *
   * @return FormFactory
   *    PluginManager instance for specified plugin type.
   */
  static public function getInstance($plugin) {
    return new self($plugin);
  }

  /**
   * FormFactory constructor.
   *
   * @param string $plugin
   *    Either a plugin type or its configuration entity type name.
   */
  public function __construct($plugin) {
    $this->plugin = str_replace('integration_', '', $plugin);

    $this->controllers = module_invoke_all("integration_ui_form_controllers");
    drupal_alter("integration_ui_form_controllers", $this->controllers);

    $this->pluginManager = PluginManager::getInstance($this->plugin);
  }

  /**
   * Return new form controller instance for current plugin type.
   *
   * @return FormInterface
   *    Form handler instance
   */
  public function getController() {
    // @todo Throw exception is not set.
    if (isset($this->controllers[$this->plugin])) {
      return new $this->controllers[$this->plugin]();
    }
  }

  /**
   * Get form handler for current plugin type.
   *
   * @param string $plugin
   *    Plugin name.
   *
   * @return FormInterface
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
   * @return FormInterface
   *    Form handler instance
   */
  public function getComponentHandler($component) {
    $form_handler = $this->pluginManager->getComponent($component)->getFormHandler();
    try {
      return new $form_handler();
    } catch (\InvalidArgumentException $e) {
      watchdog_exception('integration', $e);
    }
  }

}

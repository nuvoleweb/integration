<?php

/**
 * @file
 * Contains \Drupal\integration_ui\FormHandlerFactory.
 */

namespace Drupal\integration_ui;

use Drupal\integration\Plugins\PluginManager;
use Drupal\integration_ui\Exceptions\UndefinedFormControllerException;
use Drupal\integration_ui\Exceptions\UndefinedFormHandlerException;
use Mockery\CountValidator\Exception;

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
  protected $controllers = [];

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
   *
   * @throws UndefinedFormControllerException
   */
  public function getController() {
    if (isset($this->controllers[$this->plugin])) {
      return new $this->controllers[$this->plugin]();
    }
    else {
      throw new UndefinedFormControllerException($this->plugin);
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
   *
   * @throws UndefinedFormHandlerException
   *    Throw exception if current component does not define any form handlers.
   */
  public function getPluginHandler($plugin) {
    $form_handler = $this->pluginManager->getPlugin($plugin)->getFormHandler();
    if (!$form_handler) {
      throw new UndefinedFormHandlerException('plugin', $plugin);
    }
    return new $form_handler();
  }

  /**
   * Get form handler for current plugin type.
   *
   * @param string $component
   *    Component name.
   *
   * @return FormInterface
   *    Form handler instance
   *
   * @throws UndefinedFormHandlerException
   *    Throw exception if current component does not define any form handlers.
   */
  public function getComponentHandler($component) {
    $form_handler = $this->pluginManager->getComponent($component)->getFormHandler();
    if (!$form_handler) {
      throw new UndefinedFormHandlerException('component', $component);
    }
    return new $form_handler();
  }

}

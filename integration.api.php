<?php

/**
 * @file
 * Contains API documentation.
 */

use \Drupal\integration\PluginManager;

/**
 * Return integration layer plugin definitions.
 *
 * Plugins are defined by an array having as main key one of the following
 * integration layer plugin types:
 *  - 'backend': backend-related plugins;
 *  - 'consumer': consumer-related plugins;
 *  - 'producer': producer-related plugins.
 *
 * Each plugin type can expose the following information:
 *  - 'components': list of component types compatible with the given plugin,
 *    keys being component type names.
 *  - 'form handler': handler class responsible for form building, validation
 *    and submission. Default form handler classes are provided by the
 *    integration_ui module.
 *
 * Information exposed by hook_integration_plugins() implementation is used by
 * the module's plugin manager.
 *
 * @see integration_integration_plugins()
 * @see integration_producer_integration_plugins()
 * @see integration_consumer_integration_plugins()
 * @see PluginManager::getInstance();
 * @see PluginManager::getPluginsDefinition();
 */
function hook_integration_plugins() {
  return array(
    'backend' => array(
      'label' => t('Backend'),
      'components' => array(
        'response_handler' => array(
          'label' => t('Response handler'),
        ),
        'formatter_handler' => array(
          'label' => t('Formatter handler'),
        ),
        'authentication_handler' => array(
          'label' => t('Authentication handler'),
        ),
      ),
    ),
  );
}

/**
 * Alter integration layer plugin definitions.
 *
 * @see hook_integration_plugins()
 * @see integration_ui_integration_plugins_alter()
 */
function hook_integration_plugins_alter(&$items) {
  $items['backend']['form handler'] = 'Drupal\integration_ui\Backend\BackendFormHandler';
}

<?php

/**
 * @file
 * Contains \Drupal\integration_ui\ConfigurationEntityUiController.
 */

namespace Drupal\integration_ui;

/**
 * Class ConfigurationEntityUiController.
 *
 * @package Drupal\integration_ui
 */
class ConfigurationEntityUiController extends \EntityDefaultUIController {

  // @codingStandardsIgnoreStart
  /**
   * Provides definitions for implementing hook_menu().
   *
   * @return array
   *    Menu items array, as normally returned by hook_menu().
   */
  public function hook_menu() {
    $items = parent::hook_menu();
    foreach ($items as $path => $item) {
      if ($item['page callback'] == 'entity_ui_get_form') {
        $items[$path]['page callback'] = 'integration_ui_get_form';
      }
    }
    return $items;
  }
  // @codingStandardsIgnoreEnd

}

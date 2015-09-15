<?php

/**
 * @file
 * Contains \Drupal\integration_ui\Configuration\EntityUiController.
 */

namespace Drupal\integration_ui\Configuration;

/**
 * Class EntityUiController.
 *
 * @package Drupal\integration_ui\Configuration
 */
class EntityUiController extends \EntityDefaultUIController {

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
      if (isset($item['page callback']) && $item['page callback'] == 'entity_ui_get_form') {
        $items[$path]['page callback'] = 'integration_ui_get_form';
      }
    }
    return $items;
  }
  // @codingStandardsIgnoreEnd

}

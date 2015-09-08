<?php
/**
 * @file
 * Contains FormTraits.
 */

namespace Drupal\integration\Configuration;

use Drupal\integration\PluginManager;

/**
 * Class FormTraits.
 *
 * @package Drupal\integration\Configuration
 */
trait FormTrait {

  /**
   * Format current PluginManager::getInfo() results as list of radio buttons.
   *
   * @param PluginManager $plugin_manager
   *    Current plugin manager instance.
   * @param string $title
   *    Form element #title.
   * @param mixed $default_value
   *    Form element #default_value.
   * @param bool|FALSE $required
   *    Form element #required.
   *
   * @return array
   *    Form API radio buttons element.
   */
  public function getFormRadios(PluginManager $plugin_manager, $title, $default_value, $required = FALSE) {
    $options = $plugin_manager->getFormOptions();

    $element = array(
      '#type' => 'radios',
      '#title' => $title,
      '#default_value' => $default_value,
      '#options' => $options,
      '#required' => $required,
    );
    foreach (array_keys($options) as $name) {
      $element[$name] = array('#description' => $plugin_manager->getDescription($name));
    }
    return $element;
  }

}

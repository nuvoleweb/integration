<?php

/**
 * @file
 * Contains \Drupal\integration_ui\AbstractForm.
 */

namespace Drupal\integration_ui;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Plugins\PluginManager;

/**
 * Class AbstractForm.
 *
 * @package Drupal\integration_ui
 */
abstract class AbstractForm implements FormInterface {

  /**
   * Configuration entity we are building the form for.
   *
   * @var AbstractConfiguration
   */
  protected $configuration = NULL;

  /**
   * {@inheritdoc}
   */
  public function getConfiguration(array &$form_state) {
    // @todo throw exception if none is set.
    return $form_state['build_info']['args'][0];
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginManager(array &$form_state) {
    // @todo throw exception if none is set.
    $entity_type = $form_state['build_info']['args'][2];
    return PluginManager::getInstance($entity_type);
  }

}

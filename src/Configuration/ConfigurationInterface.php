<?php

/**
 * @file
 * Contains ConfigurationInterface.
 */

namespace Drupal\integration\Configuration;

/**
 * Interface ConfigurationInterface.
 *
 * @package Drupal\integration\Configuration
 */
interface ConfigurationInterface {

  /**
   * Get configuration human readable name.
   *
   * @return string
   *    Configuration name.
   */
  public function getName();

  /**
   * Get configuration machine name.
   *
   * @return string
   *    Configuration machine name.
   */
  public function getMachineName();

  /**
   * Return a flag indicating whether the backend is enabled.
   *
   * @return int
   *    Flag indicating whether the backend is enabled.
   */
  public function getEnabled();

  /**
   * Return the exportable status of the backend.
   *
   * @return string
   *    Exportable status of the backend.
   *
   * @see integration_configuration_status_options_list()
   */
  public function getStatus();

  /**
   * Check whether the configuration is marked as "Fixed".
   *
   * @return bool
   *    TRUE if condition is met, FALSE otherwise.
   */
  public function isCustom();

  /**
   * Check whether the configuration is marked as "Fixed".
   *
   * @return bool
   *    TRUE if condition is met, FALSE otherwise.
   */
  public function isInCode();

  /**
   * Check whether the configuration is marked as "Fixed".
   *
   * @return bool
   *    TRUE if condition is met, FALSE otherwise.
   */
  public function isOverridden();

  /**
   * Check whether the configuration is marked as "Fixed".
   *
   * @return bool
   *    TRUE if condition is met, FALSE otherwise.
   */
  public function isFixed();

  /**
   * Get value of an entity info array property.
   *
   * @param string $name
   *    Entity key name.
   *
   * @return mixed|bool
   *    Entity key value if set, FALSE otherwise.
   *
   * @see entity_get_info()
   */
  public function getEntityInfoProperty($name);

  /**
   * Get entity configuration option value.
   *
   * @param string $name
   *    Entity key name.
   *
   * @return mixed|NULL
   *    Option value if set, NULL otherwise.
   */
  public function getOption($name);

  /**
   * Set entity configuration option.
   *
   * @param string $name
   *    Option name.
   * @param string $value
   *    Option value.
   */
  public function setOption($name, $value);

}

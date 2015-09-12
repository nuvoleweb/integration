<?php

/**
 * @file
 * Contains Drupal\integration_consumer\ConsumerInterface.
 */

namespace Drupal\integration_consumer;

use Drupal\integration\Backend\BackendInterface;
use Drupal\integration_consumer\Configuration\ConsumerConfigurationInterface;
use Drupal\integration\Document\DocumentInterface;

/**
 * Interface ConsumerInterface.
 *
 * @package Drupal\integration_consumer
 */
interface ConsumerInterface {

  /**
   * Define source key, to be used in setMap().
   *
   * @return array
   *    Get default source key definition.
   */
  public function getSourceKey();

  /**
   * Register a new consumer migration given its configuration.
   *
   * @param string $configuration
   *    Consumer configuration machine name.
   */
  public static function register($configuration);

  /**
   * Load local entity given its remote backend content ID.
   *
   * @param string $id
   *    Backend content ID.
   *
   * @return object|FALSE
   *    Consumed entity for given remote backend content ID, FALSE if none.
   */
  public function getDestinationEntity($id);

  /**
   * Return destination class name for current consumer plugin.
   *
   * @return string
   *    Destination class name, such as "\MigrateDestinationNode", etc.
   */
  public function getDestinationClass();

}

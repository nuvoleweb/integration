<?php

/**
 * @file
 * Contains Drupal\integration_consumer\ConsumerInterface.
 */

namespace Drupal\integration_consumer;


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
   * Entity type as defined in hook_integration_consumer_info().
   *
   * @return string
   *    Destination entity type.
   */
  public function getDestinationEntityType();

  /**
   * Return destination class name for current consumer plugin.
   *
   * @return \MigrateDestinationNode
   *    Destination class name, such as "\MigrateDestinationNode", etc.
   */
  public function getDestinationClass();

}

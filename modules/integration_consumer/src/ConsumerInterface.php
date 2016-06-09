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
   * Define Rules event name for Migrate pre-import.
   */
  const RULES_EVENT_PRE_IMPORT = 'integration_consumer_pre_import';

  /**
   * Define Rules event name for Migrate post-import.
   */
  const RULES_EVENT_POST_IMPORT = 'integration_consumer_post_import';

  /**
   * Define Rules event name for Migrate pre-rollback.
   */
  const RULES_EVENT_PRE_ROLLBACK = 'integration_consumer_pre_rollback';

  /**
   * Define Rules event name for Migrate post-rollback.
   */
  const RULES_EVENT_POST_ROLLBACK = 'integration_consumer_post_rollback';

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

  /**
   * Fetch all documents from current configuration's backend.
   */
  public function fetchAll();

}

<?php
/**
 * @file
 * Integration Consumer API documentation.
 */

use Drupal\integration_consumer\AbstractConsumer;

/**
 * Invoked before import.
 *
 * @param AbstractConsumer $consumer
 *    Consumer object performing the migration.
 *
 * @see AbstractConsumer::preImport()
 */
function hook_integration_consumer_migrate_pre_import(AbstractConsumer $consumer) {
  
}

/**
 * Invoked after import.
 *
 * @param AbstractConsumer $consumer
 *    Consumer object performing the migration.
 *
 * @see AbstractConsumer::postImport()
 */
function hook_integration_consumer_migrate_post_import(AbstractConsumer $consumer) {
  
}

/**
 * Invoked before rollback.
 *
 * @param AbstractConsumer $consumer
 *    Consumer object performing the migration.
 *
 * @see AbstractConsumer::preRollback()
 */
function hook_integration_consumer_migrate_pre_rollback(AbstractConsumer $consumer) {
  
}

/**
 * Invoked after rollback.
 *
 * @param AbstractConsumer $consumer
 *    Consumer object performing the migration.
 *
 * @see AbstractConsumer::postRollback()
 */
function hook_integration_consumer_migrate_post_rollback(AbstractConsumer $consumer) {
  
}

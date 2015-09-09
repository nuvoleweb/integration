<?php

/**
 * @file
 * Contains Drupal\integration\Consumer\ConsumerInterface.
 */

namespace Drupal\integration\Consumer;

use Drupal\integration\Backend\BackendInterface;
use Drupal\integration\Consumer\Configuration\ConsumerConfigurationInterface;
use Drupal\integration\Document\DocumentInterface;

/**
 * Interface ConsumerInterface.
 *
 * @package Drupal\integration\Consumer
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

}

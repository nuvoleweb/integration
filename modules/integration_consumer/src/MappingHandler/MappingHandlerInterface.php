<?php

/**
 * @file
 * Contains MappingHandlerInterface.
 */

namespace Drupal\integration_consumer\MappingHandler;

use Drupal\integration_consumer\AbstractConsumer;

/**
 * Interface MappingHandlerInterface.
 *
 * @package Drupal\integration_consumer
 */
interface MappingHandlerInterface {

  /**
   * Process current mapping.
   */
  public function process();

  /**
   * Return consumer object instance the mapping handler was constructed with.
   *
   * @return AbstractConsumer
   *    Consumer object.
   */
  public function getConsumer();

}

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
   *
   * @param string $destination_field
   *    Destination field name.
   * @param string|null $source_field
   *    Source field name.
   */
  public function process($destination_field, $source_field = NULL);

  /**
   * Return consumer object instance the mapping handler was constructed with.
   *
   * @return AbstractConsumer
   *    Consumer object.
   */
  public function getConsumer();

}

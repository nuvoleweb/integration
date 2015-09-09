<?php

/**
 * @file
 * Contains AbstractMappingHandler.
 */

namespace Drupal\integration_consumer\MappingHandler;

use Drupal\integration_consumer\Consumer;

/**
 * Class AbstractMappingHandler.
 *
 * @package Drupal\integration_consumer
 */
abstract class AbstractMappingHandler implements MappingHandlerInterface {

  /**
   * Current consumer object.
   *
   * @var Consumer
   */
  protected $consumer = NULL;

  /**
   * Constructor.
   *
   * @param Consumer $consumer
   *    Consumer object.
   */
  public function __construct(Consumer $consumer) {
    $this->consumer = $consumer;
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumer() {
    return $this->consumer;
  }

}

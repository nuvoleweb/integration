<?php

/**
 * @file
 * Contains AbstractMappingHandler.
 */

namespace Drupal\integration\Consumer\MappingHandler;

use Drupal\integration\Consumer\Consumer;

/**
 * Class AbstractMappingHandler.
 *
 * @package Drupal\integration\Consumer
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

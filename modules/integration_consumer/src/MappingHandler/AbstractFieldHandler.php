<?php

/**
 * @file
 * Contains AbstractMappingHandler.
 */

namespace Drupal\integration_consumer\MappingHandler;

use Drupal\integration_consumer\AbstractConsumer;

/**
 * Class AbstractMappingHandler.
 *
 * @package Drupal\integration_consumer
 */
abstract class AbstractMappingHandler implements MappingHandlerInterface {

  /**
   * Current consumer object.
   *
   * @var AbstractConsumer
   */
  protected $consumer = NULL;

  /**
   * Constructor.
   *
   * @param AbstractConsumer $consumer
   *    Consumer object.
   */
  public function __construct(AbstractConsumer $consumer) {
    $this->consumer = $consumer;
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumer() {
    return $this->consumer;
  }

}

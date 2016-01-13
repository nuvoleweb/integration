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
   * Optional source field name.
   *
   * @var string
   *    Source field name.
   */
  protected $sourceField = NULL;

  /**
   * Optional source field name.
   *
   * @var string
   *    Source field name.
   */
  protected $destinationField = NULL;

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
   * @param string $destination_field
   *    Destination field name.
   * @param string|null $source_field
   *    Source field name.
   */
  public function __construct(AbstractConsumer $consumer, $source_field, $destination_field) {
    $this->sourceField = $source_field;
    $this->destinationField = $destination_field;
    $this->consumer = $consumer;
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumer() {
    return $this->consumer;
  }

}

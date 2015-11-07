<?php

/**
 * @file
 * Contains \Drupal\integration_consumer\NodeConsumer.
 */

namespace Drupal\integration_consumer;

/**
 * Class NodeConsumer.
 *
 * @package Drupal\integration_consumer
 */
class NodeConsumer extends AbstractConsumer {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $arguments) {

    // @todo: Make the following an option set via UI.
    $this->addFieldMapping('promote')->defaultValue(FALSE);
    $this->addFieldMapping('status')->defaultValue(NODE_PUBLISHED);

    parent::__construct($arguments);
  }

  /**
   * {@inheritdoc}
   */
  public function getDestinationClass() {
    return '\MigrateDestinationNode';
  }

}

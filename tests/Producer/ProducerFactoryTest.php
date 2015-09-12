<?php

/**
 * @file
 * Contains ProducerFactoryTest.
 */

namespace Drupal\integration\Tests\Producer;

use Drupal\integration_producer\ProducerFactory;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class ProducerFactoryTest.
 *
 * @package Drupal\integration\Tests\Producer
 */
class ProducerFactoryTest extends AbstractTest {

  /**
   * Test create method.
   */
  public function testFactory() {
    $node = $this->getExportedEntityFixture('node', 'integration_test', 1);
    $producer_info = integration_producer_get_producer_info();
    $producer_class = $producer_info[$this->producerConfiguration->getPlugin()]['class'];

    $producer = ProducerFactory::getInstance('test_configuration');

    $reflection = new \ReflectionClass($producer);
    $this->assertEquals($producer_class, $reflection->getName());
  }

}

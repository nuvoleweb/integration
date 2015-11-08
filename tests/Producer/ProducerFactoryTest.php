<?php

/**
 * @file
 * Contains ProducerFactoryTest.
 */

namespace Drupal\integration\Tests\Producer;

use Drupal\integration\Plugins\PluginManager;
use Drupal\integration\Tests\AbstractTest;
use Drupal\integration_producer\ProducerFactory;

/**
 * Class ProducerFactoryTest.
 *
 * @group producer
 *
 * @package Drupal\integration\Tests\Producer
 */
class ProducerFactoryTest extends AbstractTest {

  /**
   * Test create method.
   */
  public function testFactory() {
    $manager = PluginManager::getInstance('producer');
    $producer_info = $manager->getPluginDefinitions();
    $producer_class = $producer_info[$this->producerConfiguration->getPlugin()]['class'];

    $producer = ProducerFactory::getInstance('test_configuration');

    $reflection = new \ReflectionClass($producer);
    $this->assertEquals($producer_class, $reflection->getName());
  }

}

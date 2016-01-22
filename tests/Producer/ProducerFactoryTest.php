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
 * @group factory
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

  /**
   * Test backend creation.
   */
  public function testCreate() {
    $producer = ProducerFactory::create('test');
    $this->assertNotNull($producer);

    // Test creation defaults.
    $this->assertEquals('node_producer', $producer->getConfiguration()->getPlugin());

    // Test that 'test' configuration object is actually stored.
    $configuration = ProducerFactory::loadConfiguration('test');
    $this->assertEquals('test', $configuration->getMachineName());

    $producer
      ->setBackend('filesystem')
      ->setEntityBundle('article')
      ->setResourceSchema('article')
      ->setMapping('source1', 'destination1')
      ->setMapping('source2', 'destination2');
    $this->assertEquals('article', $producer->getConfiguration()->getEntityBundle());
    $this->assertEquals('article', $producer->getConfiguration()->getResourceSchema());
    $this->assertEquals('filesystem', $producer->getConfiguration()->getBackend());

    $expected = [
      'source1' => 'destination1',
      'source2' => 'destination2',
    ];
    $this->assertEquals($expected, $producer->getConfiguration()->getPluginSetting('mapping'));
  }

}

<?php

/**
 * @file
 * Contains ConsumerFactoryTest.
 */

namespace Drupal\integration\Tests\Consumer;

use Drupal\integration\Backend\BackendFactory;
use Drupal\integration_consumer\Configuration\ConsumerConfiguration;
use Drupal\integration_consumer\ConsumerFactory;
use Drupal\integration\Plugins\PluginManager;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class ConsumerFactoryTest.
 *
 * @group consumer
 * @group factory
 *
 * @package Drupal\integration\Tests\Consumer
 */
class ConsumerFactoryTest extends AbstractTest {

  /**
   * Test create method.
   */
  public function testFactory() {
    $manager = PluginManager::getInstance('consumer');
    $consumer_info = $manager->getPluginDefinitions();
    $consumer_class = $consumer_info[$this->consumerConfiguration->getPlugin()]['class'];

    $consumer = ConsumerFactory::getInstance('test_configuration');

    $reflection = new \ReflectionClass($consumer);
    $this->assertEquals($consumer_class, $reflection->getName());
  }

  /**
   * Test consumer creation.
   */
  public function testCreate() {
    BackendFactory ::create('test');
    $consumer = ConsumerFactory::create('test', 'test');
    $this->assertNotNull($consumer);

    $consumer->setMapping('source1', 'destination1')
      ->setMapping('source2', 'destination2')
      ->setResourceSchema('schema')
      ->setEntityBundle('article');

    // Test that 'test' configuration object is actually stored.
    /** @var ConsumerConfiguration $configuration */
    $configuration = ConsumerFactory::loadConfiguration('test');
    $this->assertEquals('test', $configuration->getMachineName());

    // Test configuration properties.
    $this->assertEquals('node_consumer', $configuration->getPlugin());
    $this->assertEquals('test', $configuration->getBackend());
    $this->assertEquals('schema', $configuration->getResourceSchema());
    $this->assertEquals('article', $configuration->getEntityBundle());
    $this->assertEquals([
      'source1' => 'destination1',
      'source2' => 'destination2',
    ], $configuration->getMapping());
  }

}

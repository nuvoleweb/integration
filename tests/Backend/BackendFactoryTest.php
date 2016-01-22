<?php

/**
 * @file
 * Contains BackendFactoryTest.
 */

namespace Drupal\integration\Tests\Backend;

use Drupal\integration\Backend\BackendFactory;
use Drupal\integration\Plugins\PluginManager;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class BackendFactoryTest.
 *
 * @group backend
 * @group factory
 *
 * @package Drupal\integration\Tests\Backend
 */
class BackendFactoryTest extends AbstractTest {

  /**
   * Test create method.
   */
  public function testFactory() {
    $manager = PluginManager::getInstance('backend');
    $backend_info = $manager->getPluginDefinitions();
    $backend_class = $backend_info[$this->backendConfiguration->getPlugin()]['class'];

    $backend = BackendFactory::getInstance('test_configuration');

    $reflection = new \ReflectionClass($backend);
    $this->assertEquals($backend_class, $reflection->getName());
  }

  /**
   * Test backend creation.
   */
  public function testCreate() {
    $backend = BackendFactory::create('test');
    $this->assertNotNull($backend);

    // Test creation defaults.
    $this->assertEquals('memory_backend', $backend->getConfiguration()->getPlugin());
    $this->assertEquals('no_authentication', $backend->getConfiguration()->getAuthentication());
    $this->assertEquals('json_formatter', $backend->getConfiguration()->getFormatter());

    // Test that 'test' configuration object is actually stored.
    $configuration = BackendFactory::loadConfiguration('test');
    $this->assertEquals('test', $configuration->getMachineName());
  }

}

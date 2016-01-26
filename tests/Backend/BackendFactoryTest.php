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

    $backend->setResourceSchema('article');
    $backend->setResourceSchema('news');
    $this->assertEquals(['article', 'news'], $backend->getConfiguration()->getPluginSetting('resource_schemas'));

    $backend->setResourceSchemaSetting('article', 'name1', 'value1');
    $backend->setResourceSchemaSetting('article', 'name2', 'value2');
    $backend->setResourceSchemaSetting('news', 'name1', 'value1');
    $backend->setResourceSchemaSetting('news', 'name2', 'value2');

    $expected = [];
    $expected['article']['name1'] = 'value1';
    $expected['article']['name2'] = 'value2';
    $expected['news']['name1'] = 'value1';
    $expected['news']['name2'] = 'value2';
    $this->assertEquals($expected, $backend->getConfiguration()->getPluginSetting('resource_schema'));

    $backend->setBackendSetting('name1', 'value1');
    $backend->setBackendSetting('name2', 'value2');
    $this->assertEquals('value1', $backend->getConfiguration()->getPluginSetting('backend.name1'));
    $this->assertEquals('value2', $backend->getConfiguration()->getPluginSetting('backend.name2'));
  }

}

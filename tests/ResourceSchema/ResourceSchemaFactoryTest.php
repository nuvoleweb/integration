<?php

/**
 * @file
 * Contains ResourceSchemaFactoryTest.
 */

namespace Drupal\integration\Tests\ResourceSchema;

use Drupal\integration\Plugins\PluginManager;
use Drupal\integration\ResourceSchema\ResourceSchemaFactory;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class ResourceSchemaFactoryTest.
 *
 * @group resource_schema
 * @group factory
 *
 * @package Drupal\integration\Tests\ResourceSchema
 */
class ResourceSchemaFactoryTest extends AbstractTest {

  /**
   * Test create method.
   */
  public function testFactory() {
    $manager = PluginManager::getInstance('resource_schema');
    $resource_schema_info = $manager->getPluginDefinitions();
    $resource_schema_class = $resource_schema_info[$this->resourceSchemaConfiguration->getPlugin()]['class'];

    $resource_schema = ResourceSchemaFactory::getInstance('test_configuration');

    $reflection = new \ReflectionClass($resource_schema);
    $this->assertEquals($resource_schema_class, $reflection->getName());
  }

  /**
   * Test resource schema  creation.
   */
  public function testCreate() {
    $resource_schema = ResourceSchemaFactory::create('test');
    $this->assertNotNull($resource_schema);

    // Test creation defaults.
    $this->assertEquals('raw_resource_schema', $resource_schema->getConfiguration()->getPlugin());

    // Test that 'test' configuration object is actually stored.
    $configuration = ResourceSchemaFactory::loadConfiguration('test');
    $this->assertEquals('test', $configuration->getMachineName());

    $fields = ['title' => 'Title', 'body' => 'Body'];
    foreach ($fields as $machine_name => $label) {
      $resource_schema->setField($machine_name, $label);
    }
    $this->assertEquals($resource_schema->getConfiguration()->getPluginSetting('fields.title'), 'Title');
    $this->assertEquals($resource_schema->getConfiguration()->getPluginSetting('fields.body'), 'Body');
    $this->assertEquals($resource_schema->getConfiguration()->getPluginSetting('fields'), $fields);
  }

}

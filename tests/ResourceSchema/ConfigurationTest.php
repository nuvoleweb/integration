<?php

/**
 * @file
 * Contains ConfigurationTest.
 */

namespace Drupal\integration\Tests\ResourceSchema;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Exceptions\BaseException;
use Drupal\integration\ResourceSchema\Configuration\ResourceSchemaConfiguration;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class ConfigurationTest.
 *
 * @group resource_schema
 * @group configuration
 *
 * @package Drupal\integration\Tests\ResourceSchema
 */
class ConfigurationTest extends AbstractTest {

  /**
   * Test configuration entity CRUD operations.
   *
   * @dataProvider configurationProvider
   *
   * @expectedException \Drupal\integration\Exceptions\BaseException
   */
  public function testConfigurationEntityCrud($data) {

    $reflection = new \ReflectionClass($this->resourceSchemaConfiguration);
    $this->assertEquals('Drupal\integration\ResourceSchema\Configuration\ResourceSchemaConfiguration', $reflection->getName());

    $this->assertEquals($data->machine_name, $this->resourceSchemaConfiguration->identifier());
    $this->assertEquals(ENTITY_CUSTOM, $this->resourceSchemaConfiguration->getStatus());
    $this->assertEquals($data->settings['plugin']['title'], $this->resourceSchemaConfiguration->getPluginSetting('title'));
    $this->assertEquals($data->settings['plugin']['body'], $this->resourceSchemaConfiguration->getPluginSetting('body'));
    $this->assertEquals($data->settings['plugin']['attachment'], $this->resourceSchemaConfiguration->getPluginSetting('attachment'));

    $machine_name = $this->resourceSchemaConfiguration->identifier();
    $this->assertNotNull(ConfigurationFactory::load('integration_resource_schema', $machine_name));

    $this->resourceSchemaConfiguration->delete();
    // Should throw \InvalidArgumentException exception.
    ConfigurationFactory::load('integration_resource_schema', $machine_name, TRUE);
  }

  /**
   * Test export entity.
   *
   * @param object $data
   *    Configuration data.
   *
   * @dataProvider configurationProvider
   */
  public function testExportImport($data) {

    /** @var ResourceSchemaConfiguration $configuration */
    $configuration = ConfigurationFactory::create('resource_schema', 'test_configuration', (array) $data);

    $json = entity_export('integration_resource_schema', $configuration);
    $decoded = json_decode($json);
    $this->assertNotNull($decoded);
    $this->assertEquals($data->machine_name, $decoded->machine_name);

    /** @var ResourceSchemaConfiguration $entity */
    $entity = entity_import('integration_resource_schema', $json);
    $this->assertEquals($data->machine_name, $entity->identifier());
  }

  /**
   * Data provider.
   *
   * @return array
   *    Configuration objects.
   */
  public function configurationProvider() {
    return [
      [$this->getConfigurationFixture('resource_schema', 'test_configuration')],
    ];
  }

}

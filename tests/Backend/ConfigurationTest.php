<?php

/**
 * @file
 * Contains ConfigurationTest.
 */

namespace Drupal\integration\Tests\Backend;

use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class ConfigurationTest.
 *
 * @package Drupal\integration\Tests\Backend
 */
class ConfigurationTest extends AbstractTest {

  /**
   * Test configuration entity CRUD operations.
   *
   * @dataProvider configurationProvider
   *
   * @expectedException \InvalidArgumentException
   */
  public function testConfigurationEntityCrud($data) {

    $reflection = new \ReflectionClass($this->backendConfiguration);
    $this->assertEquals('Drupal\integration\Backend\Configuration\BackendConfiguration', $reflection->getName());

    $this->assertEquals($data->machine_name, $this->backendConfiguration->identifier());
    $this->assertEquals(ENTITY_CUSTOM, $this->backendConfiguration->getStatus());
    $this->assertEquals($data->options['endpoint'], $this->backendConfiguration->getOption('endpoint'));
    $this->assertEquals($data->options['base_path'], $this->backendConfiguration->getOption('base_path'));

    $machine_name = $this->backendConfiguration->identifier();
    $this->assertNotNull(ConfigurationFactory::load('integration_backend', $machine_name));

    $this->backendConfiguration->delete();
    // Should throw \InvalidArgumentException exception.
    ConfigurationFactory::load('integration_backend', $machine_name);
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

    /** @var BackendConfiguration $configuration */
    $configuration = entity_create('integration_backend', (array) $data);

    $json = entity_export('integration_backend', $configuration);
    $decoded = json_decode($json);
    $this->assertNotNull($decoded);
    $this->assertEquals($data->machine_name, $decoded->machine_name);

    /** @var BackendConfiguration $entity */
    $entity = entity_import('integration_backend', $json);
    $this->assertEquals($data->machine_name, $entity->identifier());
    $this->assertEquals($data->options['endpoint'], $entity->getOption('endpoint'));
    $this->assertEquals($data->options['base_path'], $entity->getOption('base_path'));
  }

  /**
   * Data provider.
   *
   * @return array
   *    Configuration objects.
   */
  public function configurationProvider() {
    return array(
      array($this->getConfigurationFixture('backend', 'test_configuration')),
    );
  }

}

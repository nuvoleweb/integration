<?php

/**
 * @file
 * Contains ConfigurationTest.
 */

namespace Drupal\integration\Tests\Consumer;

use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration\Consumer\Configuration\ConsumerConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class ConfigurationTest.
 *
 * @package Drupal\integration\Tests\Consumer
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
    $reflection = new \ReflectionClass($this->consumerConfiguration);
    $this->assertEquals('Drupal\integration\Consumer\Configuration\ConsumerConfiguration', $reflection->getName());

    $this->assertEquals($data->machine_name, $this->consumerConfiguration->identifier());
    $this->assertEquals(ENTITY_CUSTOM, $this->consumerConfiguration->getStatus());

    $this->assertNotEmpty($this->consumerConfiguration->getMapping());

    $flipped = array_flip($data->mapping);
    foreach ($this->consumerConfiguration->getMapping() as $destination => $source) {
      $this->assertEquals($data->mapping[$destination], $this->consumerConfiguration->getMappingSource($destination));
      $this->assertEquals($flipped[$source], $this->consumerConfiguration->getMappingDestination($source));
    }

    $machine_name = $this->consumerConfiguration->identifier();
    $this->assertNotNull(ConfigurationFactory::load('integration_consumer', $machine_name));

    $this->assertEquals($this->backendConfiguration->getOption('base_path'), $this->consumerConfiguration->getBackendConfiguration()->getOption('base_path'));
    $this->assertEquals($this->backendConfiguration->getOption('endpoint'), $this->consumerConfiguration->getBackendConfiguration()->getOption('endpoint'));

    $this->consumerConfiguration->delete();
    // Should throw \InvalidArgumentException exception.
    ConfigurationFactory::load('integration_consumer', $machine_name);
  }

  /**
   * Test export entity.
   *
   * @dataProvider configurationProvider
   */
  public function testExportImport($data) {

    /** @var ConsumerConfiguration $configuration */
    $configuration = entity_create('integration_consumer', (array) $data);

    $json = entity_export('integration_consumer', $configuration);
    $decoded = json_decode($json);
    $this->assertNotNull($decoded);
    $this->assertEquals($data->machine_name, $decoded->machine_name);

    /** @var ConsumerConfiguration $entity */
    $entity = entity_import('integration_consumer', $json);
    $this->assertEquals($data->machine_name, $entity->identifier());
  }

  /**
   * Data provider.
   *
   * @return array
   *    Configuration objects.
   */
  public function configurationProvider() {
    return array(
      array($this->getConfigurationFixture('consumer', 'test_configuration')),
    );
  }

}

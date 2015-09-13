<?php

/**
 * @file
 * Contains ConfigurationTest.
 */

namespace Drupal\integration\Tests\Consumer;

use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration_consumer\Configuration\ConsumerConfiguration;
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
    $this->assertEquals('Drupal\integration_consumer\Configuration\ConsumerConfiguration', $reflection->getName());

    $this->assertEquals($data->machine_name, $this->consumerConfiguration->identifier());
    $this->assertEquals(ENTITY_CUSTOM, $this->consumerConfiguration->getStatus());

    $this->assertNotEmpty($this->consumerConfiguration->getMapping());

    $mapping = $data->settings['components']['mapping_handler'];
    $flipped = array_flip($mapping);
    foreach ($this->consumerConfiguration->getMapping() as $destination => $source) {
      $this->assertEquals($mapping[$destination], $this->consumerConfiguration->getMappingSource($destination));
      $this->assertEquals($flipped[$source], $this->consumerConfiguration->getMappingDestination($source));
    }

    $machine_name = $this->consumerConfiguration->identifier();
    $this->assertNotNull(ConfigurationFactory::load('integration_consumer', $machine_name));

    $backend = $this->consumerConfiguration->getBackendConfiguration();
    $this->assertNotNull($backend);
    $this->assertNotNull($backend->getPluginSetting('base_path'));
    $this->assertNotNull($backend->getPluginSetting('endpoint'));

    $this->assertEquals($this->backendConfiguration->getPluginSetting('base_path'), $this->consumerConfiguration->getBackendConfiguration()->getPluginSetting('base_path'));
    $this->assertEquals($this->backendConfiguration->getPluginSetting('endpoint'), $this->consumerConfiguration->getBackendConfiguration()->getPluginSetting('endpoint'));

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

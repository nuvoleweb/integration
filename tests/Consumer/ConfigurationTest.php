<?php

/**
 * @file
 * Contains ConfigurationTest.
 */

namespace Drupal\integration\Tests\Consumer;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Exceptions\BaseException;
use Drupal\integration\Tests\AbstractTest;
use Drupal\integration_consumer\Configuration\ConsumerConfiguration;

/**
 * Class ConfigurationTest.
 *
 * @group consumer
 * @group configuration
 *
 * @package Drupal\integration\Tests\Consumer
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
    $reflection = new \ReflectionClass($this->consumerConfiguration);
    $this->assertEquals('Drupal\integration_consumer\Configuration\ConsumerConfiguration', $reflection->getName());

    $this->assertEquals($data->machine_name, $this->consumerConfiguration->identifier());
    $this->assertEquals(ENTITY_CUSTOM, $this->consumerConfiguration->getStatus());

    $this->assertNotEmpty($this->consumerConfiguration->getMapping());

    $mapping = $data->settings['plugin']['mapping'];
    $flipped = array_flip($mapping);
    foreach ($this->consumerConfiguration->getMapping() as $destination => $source) {
      $this->assertEquals($mapping[$destination], $this->consumerConfiguration->getMappingSource($destination));
      $this->assertEquals($flipped[$source], $this->consumerConfiguration->getMappingDestination($source));
    }

    $machine_name = $this->consumerConfiguration->identifier();
    $this->assertNotNull(ConfigurationFactory::load('integration_consumer', $machine_name));

    $backend = $this->consumerConfiguration->getBackendConfiguration();
    $this->assertNotNull($backend);
    $this->assertNotNull($backend->getPluginSetting('base_url'));

    $this->assertEquals($this->backendConfiguration->getPluginSetting('base_url'), $this->consumerConfiguration->getBackendConfiguration()->getPluginSetting('base_url'));

    $this->consumerConfiguration->delete();
    // Should throw \InvalidArgumentException exception.
    ConfigurationFactory::load('integration_consumer', $machine_name, TRUE);
  }

  /**
   * Test export entity.
   *
   * @dataProvider configurationProvider
   */
  public function testExportImport($data) {

    /** @var ConsumerConfiguration $configuration */
    $configuration = ConfigurationFactory::create('consumer', 'test_configuration', (array) $data);

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
    return [
      [$this->getConfigurationFixture('consumer', 'test_configuration')],
    ];
  }

}

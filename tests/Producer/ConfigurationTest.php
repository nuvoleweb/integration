<?php

/**
 * @file
 * Contains ConfigurationTest.
 */

namespace Drupal\integration\Tests\Producer;

use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Exceptions\BaseException;
use Drupal\integration\Tests\AbstractTest;
use Drupal\integration_producer\Configuration\ProducerConfiguration;

/**
 * Class ConfigurationTest.
 *
 * @group producer
 * @group configuration
 *
 * @package Drupal\integration\Tests\Producer
 */
class ConfigurationTest extends AbstractTest {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    global $conf;
    $conf['integration_producer_id'] = 'producer-id';
  }

  /**
   * Test configuration entity CRUD operations.
   *
   * @dataProvider configurationProvider
   *
   * @expectedException \Drupal\integration\Exceptions\BaseException
   */
  public function testConfigurationEntityCrud($data) {
    $reflection = new \ReflectionClass($this->producerConfiguration);
    $this->assertEquals('Drupal\integration_producer\Configuration\ProducerConfiguration', $reflection->getName());

    $this->assertEquals($data->machine_name, $this->producerConfiguration->identifier());
    $this->assertEquals(ENTITY_CUSTOM, $this->producerConfiguration->getStatus());
    $this->assertEquals('producer-id', $this->producerConfiguration->getProducerId());

    $this->assertEquals($data->entity_bundle, $this->producerConfiguration->getEntityBundle());

    $machine_name = $this->producerConfiguration->identifier();
    $this->assertNotNull(ConfigurationFactory::load('integration_producer', $machine_name));

    $this->producerConfiguration->delete();
    // Should throw \InvalidArgumentException exception.
    ConfigurationFactory::load('integration_producer', $machine_name, TRUE);
  }

  /**
   * Test export entity.
   *
   * @dataProvider configurationProvider
   */
  public function testExportImport($data) {

    /** @var ProducerConfiguration $configuration */
    $configuration = ConfigurationFactory::create('producer', 'test_configuration', (array) $data);

    $json = entity_export('integration_producer', $configuration);
    $decoded = json_decode($json);
    $this->assertNotNull($decoded);
    $this->assertEquals($data->machine_name, $decoded->machine_name);

    /** @var ProducerConfiguration $entity */
    $entity = entity_import('integration_producer', $json);
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
      [$this->getConfigurationFixture('producer', 'test_configuration')],
    ];
  }

}

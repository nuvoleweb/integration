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
 * @group backend
 * @group configuration
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

    $this->checkConsistency($data, $this->backendConfiguration);

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
    $configuration = entity_import('integration_backend', $json);
    $this->checkConsistency($data, $configuration);
  }

  /**
   * Run consistency tests over configuration entity properties.
   *
   * @param mixed $data
   *    Expected configuration data, fetched from fixtures.
   * @param BackendConfiguration $configuration
   *    Actual configuration entity.
   */
  private function checkConsistency($data, $configuration) {

    $this->assertEquals($data->machine_name, $configuration->identifier());
    $this->assertEquals(ENTITY_CUSTOM, $configuration->getStatus());

    $actual = $configuration->getPluginSetting('base_url');
    $this->assertNotEmpty($actual);
    $this->assertEquals($data->settings['plugin']['base_url'], $actual);

    $resource_schema = $data->settings['plugin']['resource_schema'];
    $mapping = [
      'resource_schema.foo.base_path' => $resource_schema['foo']['base_path'],
      'resource_schema.foo.endpoint' => $resource_schema['foo']['endpoint'],
      'resource_schema.test_configuration.base_path' => $resource_schema['test_configuration']['base_path'],
      'resource_schema.test_configuration.endpoint' => $resource_schema['test_configuration']['endpoint'],
    ];
    foreach ($mapping as $name => $expected) {
      $actual = $configuration->getPluginSetting($name);
      $this->assertNotEmpty($actual);
      $this->assertEquals($expected, $actual);
    }

    $components_data = $data->settings['components'];
    foreach ($components_data as $component => $settings) {
      $actual = $configuration->getComponentSettings($component);
      $this->assertNotEmpty($actual);
      $this->assertEquals($data->{$component}, $configuration->{$component});
      foreach ($settings as $name => $expected) {
        $this->assertEquals($expected, $configuration->getComponentSetting($component, $name));
      }
    }
  }

  /**
   * Data provider.
   *
   * @return array
   *    Configuration objects.
   */
  public function configurationProvider() {
    return [
      [$this->getConfigurationFixture('backend', 'test_configuration')],
    ];
  }

}

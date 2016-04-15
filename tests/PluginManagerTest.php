<?php

/**
 * @file
 * Contains Drupal\integration\Tests\PluginManager.
 */

namespace Drupal\integration\Tests;

use Drupal\integration\Plugins\PluginManager;

/**
 * Class PluginManager.
 *
 * @group hooks
 * @group plugin-manager
 *
 * @package Drupal\integration\Tests
 */
class PluginManagerTest extends \PHPUnit_Framework_TestCase {

  /**
   * Test plugin and plugin component definitions.
   *
   * @group run
   */
  public function testDefinitions() {

    // Test backend plugin definitions.
    $manager = PluginManager::getInstance('backend');
    $expected = [
      'memory_backend',
      'filesystem_backend',
    ];
    $this->assertEquals($expected, array_keys($manager->getPluginDefinitions()));

    // Test backend plugin component definitions.
    $expected = [
      'json_formatter',
      'http_authentication',
      'no_authentication',
    ];
    $this->assertEquals($expected, array_keys($manager->getComponentDefinitions()));

    $expected = [
      'http_authentication',
      'no_authentication',
    ];
    $this->assertEquals($expected, array_keys($manager->getComponentDefinitions('authentication_handler')));

    // Test resource_schema plugin definitions.
    $manager = PluginManager::getInstance('resource_schema');
    $expected = [
      'raw_resource_schema',
    ];
    $this->assertEquals($expected, array_keys($manager->getPluginDefinitions()));

    // Test consumer plugin definitions.
    $manager = PluginManager::getInstance('consumer');
    $expected = [
      'node_consumer',
    ];
    $this->assertEquals($expected, array_keys($manager->getPluginDefinitions()));

    // Test consumer plugin component definitions.
    $expected = [
      'file_field_mapping',
      'text_with_summary_mapping',
      'title_mapping',
    ];
    $this->assertEquals($expected, array_keys($manager->getComponentDefinitions()));
    $this->assertEquals($expected, array_keys($manager->getComponentDefinitions('mapping_handler')));

    // Test producer plugin definitions.
    $manager = PluginManager::getInstance('producer');
    $expected = [
      'node_producer',
      'taxonomy_term_producer',
    ];
    $this->assertEquals($expected, array_keys($manager->getPluginDefinitions()));

    // Test producer plugin component definitions.
    $expected = [
      'default',
      'text',
      'text_long',
      'text_with_summary',
      'date',
      'datetime',
      'datestamp',
      'file',
      'image',
      'taxonomy_term_reference',
    ];
    $this->assertEquals($expected, array_keys($manager->getComponentDefinitions()));
  }

  /**
   * Test plugin definitions.
   *
   * @group run
   */
  public function testPluginDefinitions() {

    foreach (['backend', 'consumer', 'producer', 'resource_schema'] as $plugin) {
      $manager = PluginManager::getInstance($plugin);
      $definitions = $manager->getComponentDefinitions();

      foreach ($definitions as $name => $definition) {
        $this->assertEquals($definition['label'], $manager->getComponent($name)->getLabel());
        $this->assertEquals($definition['class'], $manager->getComponent($name)->getClass());
        $this->assertEquals($definition['description'], $manager->getComponent($name)->getDescription());
        $this->assertEquals($definition['type'], $manager->getComponent($name)->getType());
      }
    }
  }

  /**
   * Test plugin component definitions.
   *
   * @group run
   */
  public function testPluginComponentDefinitions() {

    foreach (['backend', 'consumer', 'producer', 'resource_schema'] as $plugin) {
      $manager = PluginManager::getInstance($plugin);
      $definitions = $manager->getPluginDefinitions();

      foreach ($definitions as $name => $definition) {
        $this->assertEquals($definition['label'], $manager->getPlugin($name)->getLabel());
        $this->assertEquals($definition['class'], $manager->getPlugin($name)->getClass());
        $this->assertEquals($definition['description'], $manager->getPlugin($name)->getDescription());
      }
    }
  }

}

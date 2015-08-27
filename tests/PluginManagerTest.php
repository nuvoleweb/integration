<?php

/**
 * @file
 * Contains Drupal\integration\Tests\PluginManager.
 */

namespace Drupal\integration\Tests;

use Drupal\integration\PluginManager;

/**
 * Class PluginManager.
 *
 * @package Drupal\integration\Tests
 */
class PluginManagerTest extends \PHPUnit_Framework_TestCase {

  /**
   * Test plugin manager construction.
   */
  public function testConstruction() {

    $expected = array(
      'response_handler',
      'formatter_handler',
      'authentication_handler',
    );
    $this->assertEquals($expected, PluginManager::getInstance('backend')->getComponents());

    $expected = array(
      'mapping_handler',
    );
    $this->assertEquals($expected, PluginManager::getInstance('consumer')->getComponents());

    $expected = array(
      'field_handler',
    );
    $this->assertEquals($expected, PluginManager::getInstance('producer')->getComponents());
  }

  /**
   * Test info hooks.
   */
  public function testInfo() {

    $info = PluginManager::getInstance('consumer')->setComponent('mapping_handler')->getInfo();
    $expected = array(
      'file_field_mapping',
      'text_with_summary_mapping',
      'title_mapping',
    );
    $this->assertEquals($expected, array_keys($info));

    $manager = PluginManager::getInstance('backend');
    $data = integration_integration_backend_info();
    $this->assertFromData($manager, $data);

    $manager = PluginManager::getInstance('backend')->setComponent('response_handler');
    $data = integration_integration_backend_response_handler_info();
    $this->assertFromData($manager, $data);

    $manager = PluginManager::getInstance('backend')->setComponent('formatter_handler');
    $data = integration_integration_backend_formatter_handler_info();
    $this->assertFromData($manager, $data);

    $manager = PluginManager::getInstance('consumer')->setComponent('mapping_handler');
    $data = integration_consumer_integration_consumer_mapping_handler_info();
    $this->assertFromData($manager, $data);

    $manager = PluginManager::getInstance('producer');
    $data = integration_producer_get_producer_info();
    $this->assertFromData($manager, $data);

    $manager = PluginManager::getInstance('producer')->setComponent('field_handler');
    $data = integration_producer_integration_producer_field_handler_info();
    $this->assertFromData($manager, $data);
  }

  /**
   * Assert that all plugin properties are set correctly.
   *
   * @param PluginManager $manager
   *    PluginManager instance.
   * @param array $data
   *    Data to test our assertions against.
   */
  public function assertFromData(PluginManager $manager, array $data) {
    foreach ($data as $name => $info) {
      $this->assertEquals($info['label'], $manager->getLabel($name));
      $this->assertEquals($info['class'], $manager->getClass($name));
      $this->assertEquals($info['description'], $manager->getDescription($name));
    }
  }

}

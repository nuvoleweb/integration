<?php

/**
 * @file
 * Contains ConfigurationTest.
 */

namespace Drupal\integration\Tests\Configuration;

use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class ConfigurationTest.
 *
 * @group configuration
 *
 * @package Drupal\integration\Tests\Configuration
 */
class ConfigurationTest extends AbstractTest {

  /**
   * Test configuration properties setters.
   */
  public function testSetters() {
    /** @var AbstractConfiguration $configuration */
    $configuration = ConfigurationFactory::create('backend', 'test');
    $expected_settings = [
      'a' => [
        'b' => [
            'c' => 1,
            'd' => 2,
        ],
        'e' => ['f' => 3],
      ],
      'g' => 4,
    ];

    $configuration->setPluginSetting('a.b.c', 1);
    $configuration->setPluginSetting('a.b.d', 2);
    $configuration->setPluginSetting('a.e.f', 3);
    $configuration->setPluginSetting('g', 4);
    $this->assertEquals($expected_settings, $configuration->getPluginSettings());

    $configuration->setComponentSetting('component', 'a.b.c', 1);
    $configuration->setComponentSetting('component', 'a.b.d', 2);
    $configuration->setComponentSetting('component', 'a.e.f', 3);
    $configuration->setComponentSetting('component', 'g', 4);
    $this->assertEquals($expected_settings, $configuration->getComponentSettings('component'));
  }

  /**
   * Test configuration properties getters.
   */
  public function testGetters() {
    /** @var AbstractConfiguration $configuration */
    $configuration = ConfigurationFactory::create('backend', 'test_configuration');
    $configuration->setPluginSetting('a', [
      'b' => ['c' => 1],
      'd' => ['e' => 2],
    ]);
    $this->assertEquals(1, $configuration->getPluginSetting('a.b.c'));
    $this->assertEquals(2, $configuration->getPluginSetting('a.d.e'));

    $configuration->setComponentSetting('component', 'a', [
      'b' => ['c' => 1],
      'd' => ['e' => 2],
    ]);
    $this->assertEquals(1, $configuration->getComponentSetting('component', 'a.b.c'));
    $this->assertEquals(2, $configuration->getComponentSetting('component', 'a.d.e'));
  }

}

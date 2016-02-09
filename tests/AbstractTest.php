<?php

/**
 * @file
 * Contains Drupal\integration\Tests\AbstractTest.
 */

namespace Drupal\integration\Tests;

use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\ResourceSchema\Configuration\ResourceSchemaConfiguration;
use Drupal\integration_consumer\Configuration\ConsumerConfiguration;
use Drupal\integration_producer\Configuration\ProducerConfiguration;

/**
 * Class AbstractTest.
 *
 * @package Drupal\integration\Tests
 */
abstract class AbstractTest extends \PHPUnit_Framework_TestCase {

  /**
   * Reference to backend configuration object.
   *
   * @var BackendConfiguration
   */
  public $backendConfiguration = NULL;

  /**
   * Reference to producer configuration object.
   *
   * @var ProducerConfiguration
   */
  public $producerConfiguration = NULL;

  /**
   * Reference to backend configuration object.
   *
   * @var ConsumerConfiguration
   */
  public $consumerConfiguration = NULL;

  /**
   * Reference to backend configuration object.
   *
   * @var ResourceSchemaConfiguration
   */
  public $resourceSchemaConfiguration = NULL;


  /**
   * Setup PHPUnit hook.
   */
  public function setUp() {
    parent::setUp();
    $GLOBALS['base_url'] = 'http://example.com';

    $data = $this->getConfigurationFixture('backend', 'test_configuration');
    $this->backendConfiguration = ConfigurationFactory::create('backend', 'test_configuration', (array) $data);
    $this->backendConfiguration->save();

    $data = $this->getConfigurationFixture('producer', 'test_configuration');
    $this->producerConfiguration = ConfigurationFactory::create('producer', 'test_configuration', (array) $data);
    $this->producerConfiguration->save();

    $data = $this->getConfigurationFixture('consumer', 'test_configuration');
    $this->consumerConfiguration = ConfigurationFactory::create('consumer', 'test_configuration', (array) $data);
    $this->consumerConfiguration->save();

    $data = $this->getConfigurationFixture('resource_schema', 'test_configuration');
    $this->resourceSchemaConfiguration = ConfigurationFactory::create('resource_schema', 'test_configuration', (array) $data);
    $this->resourceSchemaConfiguration->save();
  }

  /**
   * PHPUnit hook.
   */
  public function tearDown() {
    parent::tearDown();

    $this->backendConfiguration->delete();
    $this->producerConfiguration->delete();
    $this->consumerConfiguration->delete();
    $this->resourceSchemaConfiguration->delete();
  }

  /**
   * Get configuration fixture.
   *
   * @param string $type
   *    Configuration type.
   * @param string $name
   *    Configuration machine name.
   *
   * @return \stdClass
   *    Configuration settings object.
   */
  protected function getConfigurationFixture($type, $name) {
    static $fixtures = [];
    if (!isset($fixtures[$type][$name])) {
      $export = new \stdClass();
      include $this->getFixturePath() . "/configuration/$type-$name.php";
      $fixtures[$type][$name] = clone $export;
    }
    return $fixtures[$type][$name];
  }

  /**
   * Get exported entity from fixture directory.
   *
   * @param string $entity_type
   *    Entity type.
   * @param string $bundle
   *    Bundle.
   * @param int $id
   *    Entity ID.
   *
   * @return \stdClass
   *    Entity object.
   */
  protected function getExportedEntityFixture($entity_type, $bundle, $id) {
    static $fixtures = [];
    if (!isset($fixtures[$bundle][$id])) {
      $export = new \stdClass();
      include $this->getFixturePath() . "/entities/$entity_type-$bundle-$id.php";
      $fixtures[$bundle][$id] = clone $export;
    }
    return $fixtures[$bundle][$id];
  }

  /**
   * Node fixture data provider.
   *
   * @return array
   *    List of fixtures types and IDs.
   */
  public function nodeFixturesDataProvider() {
    return [
      ['integration_test', 1],
      ['integration_test', 2],
      ['integration_test', 3],
    ];
  }

  /**
   * Return expected document ID.
   *
   * @param object $node
   *    Node object.
   *
   * @return string
   *    Expected document ID.
   */
  protected function expectedNodeDocumentId($node) {
    return 'node-integration-test-' . $node->nid;
  }

  /**
   * Get root fixture path.
   *
   * @return string
   *    Root fixture path.
   */
  protected function getFixturePath() {
    return dirname(__FILE__) . '/fixtures';
  }

}

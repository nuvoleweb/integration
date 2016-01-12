<?php

/**
 * @file
 * Contains \Drupal\integration\Tests\Backend\BackendEntityControllerTest
 */

namespace Drupal\integration\Tests\Backend;

use Drupal\integration\Backend\Entity\BackendEntityController;

/**
 * Class BackendEntityControllerTest.
 *
 * @package Drupal\integration\Tests\Backend
 */
class BackendEntityControllerTest extends \PHPUnit_Framework_TestCase {

  /**
   * Reference to BackendEntityController instance.
   *
   * @var BackendEntityController
   *    Controller instance.
   */
  protected $controller = NULL;

  /**
   * Entities created during test execution.
   *
   * @var array
   *    Array of entities.
   */
  protected $entities = [];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    foreach ($this->dataProvider() as $args) {
      $values = [
        'backend_name' => $args[0],
        'backend_id' => $args[1],
        'entity_type' => $args[2],
        'entity_id' => $args[3],
      ];
      $entity = entity_create('integration_backend_entity', $values);
      entity_save('integration_backend_entity', $entity);
      $this->entities[] = $entity->id;
    }

    /** @var BackendEntityController $controller */
    $this->controller = entity_get_controller('integration_backend_entity');
    parent::setUp();
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown() {
    foreach ($this->entities as $id) {
      entity_delete('integration_backend_entity', $id);
    }
    parent::tearDown();
  }

  /**
   * Test entity controller getter.
   */
  public function testClassGetter() {

    /** @var BackendEntityController $controller */
    $controller = entity_get_controller('integration_backend_entity');
    $reflection = new \ReflectionClass($controller);
    $this->assertEquals('Drupal\integration\Backend\Entity\BackendEntityController', $reflection->getName());
  }

  /**
   * Test CRUD methods.
   */
  public function testCrudMethods() {

    $results = $this->controller->loadByConditions('test_backend_1');
    foreach ($results as $entity) {
      $this->assertEquals('test_backend_1', $entity->backend_name);
    }

    $results = $this->controller->loadByConditions(NULL, NULL, 'taxonomy_term', NULL);
    foreach ($results as $entity) {
      $this->assertEquals('test_backend_4', $entity->backend_name);
    }

    $entity = $this->controller->loadByEntity('taxonomy_term', 1);
    $this->assertEquals('test_backend_4', $entity->backend_name);

    $this->controller->deleteByEntity('taxonomy_term', 1);
    $entity = $this->controller->loadByEntity('taxonomy_term', 1);
    $this->assertNull($entity);
  }

  /**
   * Data provider.
   *
   * @return array
   *    List of mapping entity values.
   */
  public function dataProvider() {
    return [
      ['test_backend_1', '1', 'node', 1],
      ['test_backend_1', '2', 'node', 2],
      ['test_backend_2', '1', 'node', 3],
      ['test_backend_2', '2', 'node', 4],
      ['test_backend_3', '1', 'node', 5],
      ['test_backend_4', '1', 'taxonomy_term', 1],
      ['test_backend_4', '2', 'taxonomy_term', 2],
    ];
  }

}

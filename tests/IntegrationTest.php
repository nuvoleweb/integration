<?php

/**
 * @file
 * Contains Drupal\integration\Tests\IntegrationTest.
 */

namespace Drupal\integration\Tests;

use Drupal\integration\Backend\BackendFactory;
use Drupal\integration\ResourceSchema\ResourceSchemaFactory;
use Drupal\integration_consumer\ConsumerFactory;
use Drupal\integration_producer\ProducerFactory;

/**
 * Class IntegrationTest.
 *
 * @package Drupal\integration\Tests
 */
class IntegrationTest extends AbstractTest {

  /**
   * {@inheritdoc}
   */
  public function tearDown() {
    $consumer = ConsumerFactory::getInstance('test_configuration');
    $consumer->processRollback();
    parent::tearDown();
  }

  /**
   * Test producer-consumer workflow.
   */
  public function testProducerConsumerWorkflow() {

    // Get backend, producer and consumer instances.
    $backend = BackendFactory::getInstance('test_configuration');
    $consumer = ConsumerFactory::getInstance('test_configuration');
    $resource_schema = 'test_configuration';

    // Push all fixture nodes to given backend.
    foreach ($this->getProducerNodes() as $node) {
      $document = ProducerFactory::getInstance('test_configuration')->build($node);
      $this->assertNotEmpty($document->getFields());
      $this->assertNull($document->getId());
      $document = $backend->create($resource_schema, $document);
      $this->assertNotEmpty($document->getId());
    }

    // Consume documents from backend.
    $consumer->processImport();

    // Assert that title and body have been imported correctly.
    foreach ($backend->listDocuments($resource_schema) as $id) {

      $document = $backend->read($resource_schema, $id);
      $node = $consumer->getDestinationEntity($id);

      foreach (['en', 'fr'] as $language) {
        $document->setCurrentLanguage($language);
        $this->assertEquals($document->getFieldValue('title'), $node->title_field[$language][0]['value']);
        $this->assertEquals($document->getFieldValue('body'), $node->body[$language][0]['value']);
      }
    }

  }

  /**
   * Get a list of loaded nodes from fixtures.
   *
   * @return array
   *    List of node objects.
   */
  private function getProducerNodes() {
    $nodes = [];
    foreach ($this->nodeFixturesDataProvider() as $row) {
      $nodes[] = $this->getExportedEntityFixture('node', $row[0], $row[1]);
    }
    return $nodes;
  }

}

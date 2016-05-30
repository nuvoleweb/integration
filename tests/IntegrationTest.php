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
      $document = ProducerFactory::getInstance('test_configuration')->push($node);
      $this->assertNotEmpty($document->getFields());
      $this->assertNotEmpty($document->getId());
    }

    // Consume documents from backend.
    $consumer->processImport();

    // Assert that title and body have been imported correctly.
    foreach ($backend->find($resource_schema, []) as $id) {

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
   * Test interaction with non-translatable entities.
   */
  public function testNonTranslatableEntities() {

    ResourceSchemaFactory::create('article')
      ->setField('title', 'Title')
      ->setField('image', 'Image')
      ->setField('body', 'Body');

    BackendFactory::create('backend')
      ->setResourceSchema('article');

    $producer = ProducerFactory::create('article')
      ->setBackend('backend')
      ->setEntityBundle('article')
      ->setResourceSchema('article')
      ->setMapping('title', 'title')
      ->setMapping('field_image', 'image')
      ->setMapping('body', 'body');

    $node = $this->getExportedEntityFixture('node', 'article', 1);
    $document = $producer->build($node);
    $node_wrapper = entity_metadata_wrapper('node', $node);
    $this->assertEquals($node_wrapper->title->value(), $document->getFieldValue('title'));
    $this->assertEquals($node_wrapper->body->value()['value'], $document->getFieldValue('body'));
    $this->assertEquals($node_wrapper->field_image->value()['filesize'], $document->getFieldValue('image_size'));
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

<?php

/**
 * @file
 * Contains Drupal\integration\Tests\IntegrationTest.
 */

namespace Drupal\integration\Tests;

use Drupal\integration\Backend\BackendFactory;
use Drupal\integration\Consumer\Consumer;
use Drupal\integration\Producer\ProducerFactory;

/**
 * Class IntegrationTest.
 *
 * @package Drupal\integration\Tests
 */
class IntegrationTest extends AbstractTest {

  /**
   * Test producer-consumer workflow.
   */
  public function testProducerConsumerWorkflow() {

    // Get backend, producer and consumer instances.
    $backend = BackendFactory::getInstance('test_configuration');
    $consumer = Consumer::getInstance('test_configuration');

    // Make sure we have no test leftovers.
    // @todo: remove rollback from test execution.
    $consumer->processRollback();

    // Push all fixture nodes to given backend.
    foreach ($this->getProducerNodes() as $node) {
      $document = ProducerFactory::getInstance('test_configuration', $node)->build();
      $backend->create($document);
    }

    // Consume documents from backend.
    $consumer->processImport();

    // Assert that title and body have been imported correctly.
    foreach ($backend->getDocumentList() as $id) {

      $document = $backend->read($id);
      $node = $consumer->getDestinationEntity($id);

      foreach (array('en', 'fr') as $language) {
        $document->setCurrentLanguage($language);
        $this->assertEquals($document->getFieldValue('title_field'), $node->title_field[$language][0]['value']);
        $this->assertEquals($document->getFieldValue('body'), $node->body[$language][0]['value']);
      }
    }

    // Remove nodes from consumer.
    // @todo: remove rollback from test execution.
    $consumer->processRollback();
  }

  /**
   * Get a list of loaded nodes from fixtures.
   *
   * @return array
   *    List of node objects.
   */
  private function getProducerNodes() {
    $nodes = array();
    foreach ($this->nodeFixturesDataProvider() as $row) {
      $nodes[] = $this->getExportedEntityFixture('node', $row[0], $row[1]);
    }
    return $nodes;
  }

}

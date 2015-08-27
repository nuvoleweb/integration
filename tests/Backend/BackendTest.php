<?php

/**
 * @file
 * Contains BackendTest.
 */

namespace Drupal\integration\Tests\Backend;

use Drupal\integration\Backend\BackendFactory;
use Drupal\integration\Producer\ProducerFactory;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class BackendTest.
 *
 * @package Drupal\integration\Tests\Backend
 */
class BackendTest extends AbstractTest {

  /**
   * Test backend CRUD operations.
   *
   * @param string $bundle
   *    Node bundle.
   * @param int $id
   *    Node ID.
   *
   * @dataProvider nodeFixturesDataProvider
   */
  public function testBackendCrudOperations($bundle, $id) {
    $node = $this->getExportedEntityFixture('node', $bundle, $id);

    // Get backend, producer and consumer instances.
    $backend = BackendFactory::getInstance('test_configuration');
    $producer = ProducerFactory::getInstance('test_configuration', $node);

    // Build document: at this point it should not have a remote ID.
    $document = $producer->build();
    $this->assertNull($document->getId());

    // Each backend is responsible for fetching a document's remote ID.
    $this->assertEquals($this->expectedNodeDocumentId($node), $backend->getBackendContentId($document));

    // Test backend create method.
    $document = $backend->create($document);

    // Test that backend create does assign an ID to a document.
    $this->assertEquals($this->expectedNodeDocumentId($node), $document->getId());

    // Test backend read method.
    $document = $backend->read($document->getId());

    // Test backend update method.
    $document->setCurrentLanguage('en')->setField('title_field', 'English title updated');
    $updated_document = $backend->update($document);
    $this->assertEquals('English title updated', $updated_document->getFieldValue('title_field'));

    // Test backend delete method.
    $id = $updated_document->getId();
    $backend->delete($id);
    $this->assertFalse($backend->read($id));
  }

}

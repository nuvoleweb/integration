<?php

/**
 * @file
 * Contains BackendTest.
 */

namespace Drupal\integration\Tests\Backend;

use Drupal\integration\Backend\BackendFactory;
use Drupal\integration\Tests\AbstractTest;
use Drupal\integration_producer\ProducerFactory;

/**
 * Class BackendTest.
 *
 * @group backend
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
    $resource_schema = 'test_configuration';
    $node = $this->getExportedEntityFixture('node', $bundle, $id);

    // Get backend, producer and consumer instances.
    $backend = BackendFactory::getInstance('test_configuration');
    $producer = ProducerFactory::getInstance('test_configuration');

    // Build document: at this point it should not have a remote ID.
    $document = $producer->build($node);
    $this->assertNull($document->getId());

    // Each backend is responsible for fetching a document's remote ID.
    $this->assertEquals($this->expectedNodeDocumentId($node), $backend->getBackendContentId($document));

    // Test backend create method.
    $document = $backend->create($resource_schema, $document);

    // Test that backend create does assign an ID to a document.
    $this->assertEquals($this->expectedNodeDocumentId($node), $document->getId());

    // Test backend read method.
    $document = $backend->read($resource_schema, $document->getId());

    // Test backend update method.
    $document->setCurrentLanguage('en')->setField('title_field', 'English title updated');
    $updated_document = $backend->update($resource_schema, $document);
    $this->assertEquals('English title updated', $updated_document->getFieldValue('title_field'));

    // Test backend delete method.
    $id = $updated_document->getId();
    $backend->delete($resource_schema, $id);
    $this->assertFalse((bool) $backend->read($resource_schema, $id));
  }

}

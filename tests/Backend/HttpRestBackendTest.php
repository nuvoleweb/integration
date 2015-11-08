<?php

/**
 * @file
 * Contains HttpRestBackendTest.
 */

namespace Drupal\integration\Tests\Backend;

use Drupal\integration\Backend\Formatter\JsonFormatter;
use Drupal\integration\Backend\Response\HttpRequestResponse;
use Drupal\integration\Backend\RestBackend;
use Drupal\integration\Document\Document;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class HttpRestBackendTest.
 *
 * @group backend
 *
 * @package Drupal\integration\Tests\Backend
 */
class HttpRestBackendTest extends AbstractTest {

  /**
   * Test create method.
   */
  public function testCreate() {
    $resource_schema = 'test_configuration';
    $response = new \stdClass();
    $response->code = 200;
    $response->data = (object) ['_id' => '123'];

    $backend = $this->getMockedHttpBackendInstance($response);

    /** @var RestBackend $backend */
    $document = $backend->create($resource_schema, new Document());
    $this->assertEquals('123', $document->getId());
  }

  /**
   * Test update method.
   */
  public function testUpdate() {
    $resource_schema = 'test_configuration';
    $response = new \stdClass();
    $response->code = 200;
    $response->data = (object) ['_id' => '123'];

    $backend = $this->getMockedHttpBackendInstance($response);

    /** @var RestBackend $backend */
    $document = $backend->update($resource_schema, new Document());
    $this->assertEquals('123', $document->getId());
  }

  /**
   * Test delete method.
   */
  public function testDelete() {
    $resource_schema = 'test_configuration';
    $response = new \stdClass();
    $response->code = 200;
    $response->data = (object) ['_id' => '123'];

    $backend = $this->getMockedHttpBackendInstance($response);

    /** @var RestBackend $backend */
    $return = $backend->delete($resource_schema, '123');
    $this->assertTrue($return);
  }

  /**
   * Get mocked backend instance.
   *
   * @param string $returned_response
   *    Response that it's going to be returned by the backend.
   *
   * @return \Mockery\MockInterface
   *    Mocked object.
   */
  protected function getMockedHttpBackendInstance($returned_response) {
    $arguments = [
      $this->backendConfiguration,
      new HttpRequestResponse(),
      new JsonFormatter(),
    ];
    $backend = \Mockery::mock('Drupal\integration\Backend\RestBackend[httpRequest]', $arguments);
    $backend->shouldAllowMockingProtectedMethods()
      ->shouldReceive('httpRequest')
      ->andReturn($returned_response);
    return $backend;
  }

}

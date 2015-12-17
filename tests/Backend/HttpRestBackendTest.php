<?php

/**
 * @file
 * Contains HttpRestBackendTest.
 */

namespace Drupal\integration\Tests\Backend;

use Drupal\integration\Backend\Authentication\HttpAuthentication;
use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration\Backend\Formatter\JsonFormatter;
use Drupal\integration\Backend\Response\HttpJsonResponse;
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

    $response = $this->getMockResponse();
    $backend = $this->getMockBackend($this->backendConfiguration);
    $backend->shouldReceive('doRequest')->andReturn($response);

    /** @var RestBackend $backend */
    $document = $backend->create($resource_schema, new Document());
    $this->assertEquals('123', $document->getId());
  }

  /**
   * Test update method.
   */
  public function testUpdate() {
    $resource_schema = 'test_configuration';

    $response = $this->getMockResponse();
    $backend = $this->getMockBackend($this->backendConfiguration);
    $backend->shouldReceive('doRequest')->andReturn($response);

    /** @var RestBackend $backend */
    $document = $backend->update($resource_schema, new Document());
    $this->assertEquals('123', $document->getId());
  }

  /**
   * Test delete method.
   */
  public function testDelete() {
    $resource_schema = 'test_configuration';

    $response = $this->getMockResponse();
    $backend = $this->getMockBackend($this->backendConfiguration);
    $backend->shouldReceive('doRequest')->andReturn($response);

    /** @var RestBackend $backend */
    $return = $backend->delete($resource_schema, '123');
    $this->assertTrue($return);
  }

  /**
   * Test authentication plugin.
   */
  public function testHttpAuthentication() {
    /** @var BackendConfiguration $configuration */
    $configuration = entity_create('integration_backend', []);
    $configuration->plugin = 'rest_backend';
    $configuration->setPluginSetting('backend.base_url', 'http://example.com/v1');
    $configuration->setPluginSetting('resource_schema.article.endpoint', 'article');
    $configuration->setAuthentication('http_authentication');
    $configuration->setComponentSetting('authentication_handler', 'username', 'name');
    $configuration->setComponentSetting('authentication_handler', 'password', 'password');

    $response = $this->getMockResponse();
    $backend = $this->getMockBackend($configuration);
    $backend->shouldReceive('doRequest')
      ->withArgs(['http://name:password@example.com/v1/article/123', ['method' => 'GET']])
      ->andReturn($response);

    /** @var RestBackend $backend */
    $backend->read('article', '123');
  }

  /**
   * Get mocked backend instance.
   *
   * @return \Mockery\MockInterface
   *    Mocked object.
   */
  protected function getMockBackend($configuration) {
    // Reset internal static container.
    \Mockery::close();
    $backend = \Mockery::mock('Drupal\integration\Backend\RestBackend[doRequest]', [
      $configuration,
      new HttpJsonResponse(),
      new JsonFormatter(),
      new HttpAuthentication($configuration),
    ]);
    $backend->shouldAllowMockingProtectedMethods();
    return $backend;
  }

  /**
   * Get mock response.
   *
   * @return \stdClass
   */
  protected function getMockResponse() {
    $response = new \stdClass();
    $response->code = 200;
    $response->data = '{"_id": "123"}';
    return $response;
  }

}

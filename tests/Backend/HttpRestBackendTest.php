<?php

/**
 * @file
 * Contains HttpRestBackendTest.
 */

namespace Drupal\integration\Tests\Backend;

use Drupal\integration\Backend\Authentication\HttpAuthentication;
use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration\Backend\Formatter\JsonFormatter;
use Drupal\integration\Backend\RestBackend;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Document\Document;
use Drupal\integration\Document\DocumentInterface;
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

    $response = $this->getMockResponse(200);
    $backend = $this->getMockBackend($this->backendConfiguration);
    $backend->shouldReceive('doRequest')->andReturn($response);

    /** @var RestBackend $backend */
    $document = $backend->create($resource_schema, new Document());
    $expected = $this->getDocumentFixture();
    $this->assertEqualDocuments($expected, $document);
  }

  /**
   * Test update method.
   */
  public function testUpdate() {
    $resource_schema = 'test_configuration';

    $response = $this->getMockResponse(200);
    $backend = $this->getMockBackend($this->backendConfiguration);
    $backend->shouldReceive('doRequest')->andReturn($response);

    /** @var RestBackend $backend */
    $document = $backend->update($resource_schema, new Document());
    $expected = $this->getDocumentFixture();
    $this->assertEqualDocuments($expected, $document);
  }

  /**
   * Test delete method.
   */
  public function testDelete() {
    $resource_schema = 'test_configuration';

    $response = $this->getMockResponse(200);
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
    $configuration = ConfigurationFactory::create('backend', 'test_configuration');
    $configuration->setPlugin('rest_backend');
    $configuration->setPluginSetting('backend.base_url', 'http://example.com/v1');
    $configuration->setPluginSetting('resource_schema.article.endpoint', 'article');
    $configuration->setAuthentication('http_authentication');
    $configuration->setComponentSetting('authentication_handler', 'username', 'name');
    $configuration->setComponentSetting('authentication_handler', 'password', 'password');

    $response = $this->getMockResponse(200);
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
  protected function getMockResponse($code) {
    $response = new \stdClass();
    $response->code = $code;
    switch ($code) {
      case 200:
        $response->data = file_get_contents($this->getFixturePath() . '/responses/document.json');
        break;
    }
    return $response;
  }

  /**
   * Return fixture document object.
   *
   * @return Document
   *    Fixture document object.
   */
  protected function getDocumentFixture() {
    $data = file_get_contents($this->getFixturePath() . '/responses/document.json');
    $document = json_decode($data);
    return new Document($document);
  }

  /**
   * Assert that two document objects are equal.
   *
   * @param DocumentInterface $expected
   *    Expected document.
   * @param DocumentInterface $actual
   *    Actual document.
   */
  protected function assertEqualDocuments(DocumentInterface $expected, DocumentInterface $actual) {

    $this->assertEquals($expected->getId(), $actual->getId());
    $this->assertEquals($expected->getMetadata('version'), $actual->getMetadata('version'));
    $this->assertEquals($expected->getMetadata('producer_content_id'), $actual->getMetadata('producer_content_id'));
    $this->assertEquals($expected->getMetadata('producer'), $actual->getMetadata('producer'));
    $this->assertEquals($expected->getAvailableLanguages(), $actual->getAvailableLanguages());
    $this->assertEquals($expected->getCurrentLanguage(), $actual->getCurrentLanguage());
    $this->assertEquals($expected->getFields(), $actual->getFields());
  }

}

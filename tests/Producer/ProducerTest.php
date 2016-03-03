<?php

/**
 * @file
 * Contains Drupal\integration\Tests\Producer\ProducerTest.
 */

namespace Drupal\integration\Tests\Producer;

use Drupal\integration\Document\DocumentInterface;
use Drupal\integration\Tests\AbstractTest;
use Drupal\integration_producer\EntityWrapper\EntityWrapper;
use Drupal\integration_producer\NodeProducer;
use Drupal\integration_producer\ProducerFactory;
use Mockery as m;

/**
 * Class BackendTest.
 *
 * @group producer
 *
 * @package Drupal\integration\Tests\Producer\ProducerTest
 */
class ProducerTest extends AbstractTest {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    global $conf;
    $conf['integration_producer_id'] = 'producer-id';
  }

  /**
   * Test creation of a producer instance.
   */
  public function testInstance() {

    /** @var EntityWrapper $entity_wrapper */
    $entity_wrapper = m::mock('Drupal\integration_producer\EntityWrapper\EntityWrapper');
    /** @var DocumentInterface $document */
    $document = m::mock('Drupal\integration\Document\DocumentInterface');
    /** @var \Drupal\integration\Backend\MemoryBackend $backend */
    $backend = m::mock('Drupal\integration\Backend\MemoryBackend');
    /** @var \Drupal\integration\ResourceSchema\RawResourceSchema $resource */
    $resource = m::mock('Drupal\integration\ResourceSchema\RawResourceSchema');

    $producer = new NodeProducer($this->producerConfiguration, $entity_wrapper, $document, $backend, $resource);
    $reflection = new \ReflectionClass($producer);
    $this->assertEquals('Drupal\integration_producer\AbstractProducer', $reflection->getParentClass()->getName());
  }

  /**
   * Test build method.
   *
   * @param string $bundle
   *    Node bundle.
   * @param int $id
   *    Node ID.
   *
   * @dataProvider nodeFixturesDataProvider
   */
  public function testBuild($bundle, $id) {
    $node = $this->getExportedEntityFixture('node', $bundle, $id);
    $producer = ProducerFactory::getInstance('test_configuration');

    $document = $producer->build($node);

    // Assert document metadata.
    $this->assertEquals($node->language, $document->getDefaultLanguage());
    $this->assertEquals($node->language, $document->getMetadata('default_language'));
    $this->assertEquals($bundle, $document->getMetadata('type'));
    $this->assertEquals(date(EntityWrapper::DEFAULT_DATE_FORMAT, $node->created), $document->getMetadata('created'));
    $this->assertEquals(date(EntityWrapper::DEFAULT_DATE_FORMAT, $node->changed), $document->getMetadata('updated'));
    $this->assertEquals($producer->getConfiguration()->getProducerId(), $document->getMetadata('producer'));

    // Assert that available languages have been set correctly.
    $this->assertEquals(['en', 'fr'], $document->getAvailableLanguages());

    foreach (['en', 'fr'] as $language) {
      $document->setCurrentLanguage($language);

      // Assert that title has been imported correctly.
      $this->assertEquals($node->title_field[$language][0]['value'], $document->getFieldValue('title'));

      // Assert that body has been imported correctly.
      $this->assertEquals($node->body[$language][0]['value'], $document->getFieldValue('body'));

      // Assert that list field has been imported correctly.
      foreach ($document->getFieldValue('text') as $key => $value) {
        $this->assertEquals($node->field_integration_test_text[$language][$key]['value'], $value);
      }

      // Assert that images are imported correctly.
      foreach ($document->getFieldValue('images_path') as $key => $value) {
        if ($value) {
          $this->assertContains($node->field_integration_test_images[$language][$key]['filename'], urldecode($value));
        }
      }

      // Assert that image alt field is imported correctly.
      foreach ($document->getFieldValue('images_alt') as $key => $value) {
        $this->assertEquals($node->field_integration_test_images[$language][$key]['alt'], $value);
      }

      // Assert that image title field is imported correctly.
      foreach ($document->getFieldValue('images_title') as $key => $value) {
        $this->assertEquals($node->field_integration_test_images[$language][$key]['title'], $value);
      }

      // Assert that files are imported correctly.
      foreach ($document->getFieldValue('files_path') as $key => $value) {
        if ($value) {
          $this->assertContains($node->field_integration_test_files[$language][$key]['filename'], $value);
        }
      }

      // Assert that date fields has been imported correctly.
      $this->assertEquals($document->getFieldValue('dates_start'), $node->field_integration_test_dates[LANGUAGE_NONE][0]['value']);
      $this->assertEquals($document->getFieldValue('dates_end'), $node->field_integration_test_dates[LANGUAGE_NONE][0]['value2']);
      $this->assertEquals($document->getFieldValue('date_start'), $node->field_integration_test_date[LANGUAGE_NONE][0]['value']);
    }
  }

  /**
   * Test entity wrapper.
   */
  public function testEntityWrapper() {
    $node = $this->getExportedEntityFixture('node', 'integration_test', 1);
    $wrapper = new EntityWrapper('node', $node);

    $properties = [
      'nid',
      'vid',
      'type',
      'title',
      'promote',
    ];
    foreach ($properties as $property) {
      $this->assertTrue($wrapper->isProperty($property));
      $this->assertEquals($node->{$property}, $wrapper->getProperty($property));
    }
    $this->assertEquals(date(EntityWrapper::DEFAULT_DATE_FORMAT, $node->created), $wrapper->getProperty('created'));

    $fields = [
      'body',
      'field_integration_test_dates',
      'field_integration_test_files',
      'title_field',
    ];
    foreach ($fields as $field) {
      $this->assertTrue($wrapper->isField($field));
    }

    $this->assertEquals(['en', 'fr'], $wrapper->getAvailableLanguages());
    foreach (['en', 'fr'] as $language) {
      $this->assertEquals($node->title_field[$language][0]['value'], $wrapper->getField('title_field', $language));
    }
  }

}

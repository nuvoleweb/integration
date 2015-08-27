<?php

/**
 * @file
 * Contains Drupal\integration\Tests\Producer\ProducerTest.
 */

namespace Drupal\integration\Tests\Producer;

use Drupal\integration\Document\Document;
use Drupal\integration\Producer\NodeProducer;
use Drupal\integration\Document\DocumentInterface;
use Drupal\integration\Producer\EntityWrapper\EntityWrapper;
use Drupal\integration\Producer\FieldHandlers\FieldHandlerInterface;
use Drupal\integration\Producer\ProducerFactory;
use Drupal\integration\Tests\AbstractTest;
use \Mockery as m;

/**
 * Class BackendTest.
 *
 * @package Drupal\integration\Tests\Producer\ProducerTest
 */
class ProducerTest extends AbstractTest {

  /**
   * Test creation of a producer instance.
   */
  public function testInstance() {

    $entity_wrapper = m::mock('Drupal\integration\Producer\EntityWrapper\EntityWrapper');
    $document = m::mock('Drupal\integration\Document\DocumentInterface');

    $producer = new NodeProducer($this->producerConfiguration, $entity_wrapper, $document);
    $reflection = new \ReflectionClass($producer);
    $this->assertEquals('Drupal\integration\Producer\AbstractProducer', $reflection->getParentClass()->getName());
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
    $producer = ProducerFactory::getInstance('test_configuration', $node);

    $document = $producer->build();

    // Assert document metadata.
    $this->assertEquals($node->language, $document->getDefaultLanguage());
    $this->assertEquals($node->language, $document->getMetadata('default_language'));
    $this->assertEquals($bundle, $document->getMetadata('type'));
    $this->assertEquals(date(EntityWrapper::DEFAULT_DATE_FORMAT, $node->created), $document->getMetadata('created'));
    $this->assertEquals(date(EntityWrapper::DEFAULT_DATE_FORMAT, $node->changed), $document->getMetadata('updated'));
    $this->assertEquals($producer->getConfiguration()->getProducerId(), $document->getMetadata('producer'));

    // Assert that available languages have been set correctly.
    $this->assertEquals(array('en', 'fr'), $document->getAvailableLanguages());

    foreach (array('en', 'fr') as $language) {
      $document->setCurrentLanguage($language);

      // Assert that title has been imported correctly.
      $this->assertEquals($node->title_field[$language][0]['value'], $document->getFieldValue('title_field'));

      // Assert that body has been imported correctly.
      $this->assertEquals($node->body[$language][0]['value'], $document->getFieldValue('body'));

      // Assert that list field has been imported correctly.
      foreach ($document->getFieldValue('field_integration_test_text') as $key => $value) {
        $this->assertEquals($node->field_integration_test_text[$language][$key]['value'], $value);
      }

      // Assert that images are imported correctly.
      foreach ($document->getFieldValue('field_integration_test_images_path') as $key => $value) {
        if ($value) {
          $this->assertContains($node->field_integration_test_images[$language][$key]['filename'], urldecode($value));
        }
      }

      // Assert that image alt field is imported correctly.
      foreach ($document->getFieldValue('field_integration_test_images_alt') as $key => $value) {
        $this->assertEquals($node->field_integration_test_images[$language][$key]['alt'], $value);
      }

      // Assert that image title field is imported correctly.
      foreach ($document->getFieldValue('field_integration_test_images_title') as $key => $value) {
        $this->assertEquals($node->field_integration_test_images[$language][$key]['title'], $value);
      }

      // Assert that files are imported correctly.
      foreach ($document->getFieldValue('field_integration_test_files_path') as $key => $value) {
        if ($value) {
          $this->assertContains($node->field_integration_test_files[$language][$key]['filename'], $value);
        }
      }

      // Assert that date field has been imported correctly.
      $this->assertEquals($document->getFieldValue('field_integration_test_dates_start'), $node->field_integration_test_dates[LANGUAGE_NONE][0]['value']);
      $this->assertEquals($document->getFieldValue('field_integration_test_dates_end'), $node->field_integration_test_dates[LANGUAGE_NONE][0]['value2']);
    }
  }

  /**
   * Test entity wrapper.
   */
  public function testEntityWrapper() {
    $node = $this->getExportedEntityFixture('node', 'integration_test', 1);
    $wrapper = new EntityWrapper('node', $node);

    $properties = array(
      'nid',
      'vid',
      'type',
      'title',
      'promote',
    );
    foreach ($properties as $property) {
      $this->assertTrue($wrapper->isProperty($property));
      $this->assertEquals($node->{$property}, $wrapper->getProperty($property));
    }
    $this->assertEquals(date(EntityWrapper::DEFAULT_DATE_FORMAT, $node->created), $wrapper->getProperty('created'));

    $fields = array(
      'body',
      'field_integration_test_dates',
      'field_integration_test_files',
      'title_field',
    );
    foreach ($fields as $field) {
      $this->assertTrue($wrapper->isField($field));
    }

    $this->assertEquals(array('en', 'fr'), $wrapper->getAvailableLanguages());
    foreach (array('en', 'fr') as $language) {
      $this->assertEquals($node->title_field[$language][0]['value'], $wrapper->getField('title_field', $language));
    }
  }

}

<?php

/**
 * @file
 * Contains Drupal\integration\Tests\BackendTest.
 */

namespace Drupal\integration\Tests;

/**
 * Test integration hook implementation and altering.
 *
 * @package Drupal\integration\Tests\BackendTest
 */
class HooksTest extends \PHPUnit_Framework_TestCase {

  /**
   * Test hook_integration_producer_info().
   */
  public function testProducerInfo() {

    $hook_response = integration_producer_get_producer_info();
    $expected = array(
      'node' => 'Drupal\integration\Producer\NodeProducer',
      'taxonomy_term' => 'Drupal\integration\Producer\TaxonomyTermProducer',
    );
    foreach ($expected as $key => $value) {
      $this->assertTrue(isset($hook_response[$key]));
      $this->assertEquals($value, $hook_response[$key]['class']);
    }
  }

  /**
   * Test integration_backend_info().
   */
  public function testBackendInfo() {

    $hook_response = integration_backend_get_backend_info();
    $expected = array(
      'rest_backend' => 'Drupal\integration\Backend\RestBackend',
      'memory_backend' => 'Drupal\integration\Backend\MemoryBackend',
    );
    foreach ($expected as $key => $value) {
      $this->assertTrue(isset($hook_response[$key]));
      $this->assertEquals($value, $hook_response[$key]['class']);
    }
  }

  /**
   * Test hook_integration_backend_formatter_handler_info().
   */
  public function testFormatterHandlerInfo() {

    $hook_response = integration_backend_get_formatter_handler_info();
    $expected = array(
      'json_formatter' => 'Drupal\integration\Backend\Formatter\JsonFormatter',
    );
    foreach ($expected as $key => $value) {
      $this->assertTrue(isset($hook_response[$key]));
      $this->assertEquals($value, $hook_response[$key]['class']);
    }
  }

  /**
   * Test hook_integration_backend_response_handler_info().
   */
  public function testResponseHandlerInfo() {

    $hook_response = integration_backend_get_response_handler_info();
    $expected = array(
      'http_response' => 'Drupal\integration\Backend\Response\HttpRequestResponse',
      'raw_response' => 'Drupal\integration\Backend\Response\RawResponse',
    );
    foreach ($expected as $key => $value) {
      $this->assertTrue(isset($hook_response[$key]));
      $this->assertEquals($value, $hook_response[$key]['class']);
    }
  }

  /**
   * Test hook_integration_producer_field_handler_info().
   */
  public function testProducerFieldHandlersInfo() {

    $hook_response = integration_producer_get_field_handler_info();
    $expected = array(
      'default' => 'Drupal\integration\Producer\FieldHandlers\DefaultFieldHandler',
      'text' => 'Drupal\integration\Producer\FieldHandlers\TextFieldHandler',
      'text_long' => 'Drupal\integration\Producer\FieldHandlers\TextFieldHandler',
    );
    foreach ($expected as $key => $value) {
      $this->assertTrue(isset($hook_response[$key]));
      $this->assertEquals($value, $hook_response[$key]['class']);
    }
  }

  /**
   * Test hook_integration_consumer_mapping_handler_info().
   */
  public function testConsumerMappingHandlersInfo() {

    $hook_response = integration_consumer_get_mapping_handler_info();
    $expected = array(
      'title_mapping' => 'Drupal\integration\Consumer\MappingHandler\TitleMappingHandler',
      'file_field_mapping' => 'Drupal\integration\Consumer\MappingHandler\FileFieldMappingHandler',
      'text_with_summary_mapping' => 'Drupal\integration\Consumer\MappingHandler\TextWithSummaryMappingHandler',
    );
    foreach ($expected as $key => $value) {
      $this->assertTrue(isset($hook_response[$key]));
      $this->assertEquals($value, $hook_response[$key]['class']);
    }
  }

}

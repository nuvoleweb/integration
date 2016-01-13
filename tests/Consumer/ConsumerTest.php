<?php

/**
 * @file
 * Contains Drupal\integration\Tests\Consumer\ConsumerTest.
 */

namespace Drupal\integration\Tests\Consumer;

use Drupal\integration\Tests\AbstractTest;
use Drupal\integration_consumer\ConsumerFactory;

/**
 * Class ConsumerTest.
 *
 * @group consumer
 *
 * @package Drupal\integration\Tests\Consumer
 */
class ConsumerTest extends AbstractTest {

  /**
   * Setup PHPUnit hook.
   */
  public function setUp() {
    parent::setUp();
    \MigrationBase::setDisplayFunction('Drupal\integration\Tests\Consumer\ConsumerTest::migrateException');
  }

  /**
   * Migration display message callback.
   *
   * @param string $message
   *    Exception message.
   * @param string $level
   *    Exception level.
   *
   * @throws \MigrateException
   *    Throws Migrate exception only on "error" error level.
   *
   * @see \MigrationBase::$displayFunction
   * @see \MigrationBase::displayMessage()
   */
  static public function migrateException($message, $level = 'error') {
    if ($level == 'error') {
      throw new \MigrateException($message, $level);
    }
  }

  /**
   * Test creation of a consumer instance.
   */
  public function testConsumer() {
    $migration = ConsumerFactory::getInstance('test_configuration');
    $this->assertNotNull($migration);
    $mapping = $migration->getFieldMappings();
    foreach ($migration->getConfiguration()->getMapping() as $source => $destination) {
      $this->assertArrayHasKey($destination, $mapping);
      $this->assertEquals($source, $mapping[$destination]->getSourceField());
    }

    // Check that title mapping handler works correctly.
    $this->assertEquals('title', $mapping['title']->getSourceField());
    $this->assertEquals('title', $mapping['title_field']->getSourceField());
  }

}

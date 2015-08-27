<?php

/**
 * @file
 * Contains Drupal\integration\Tests\Consumer\ConsumerTest.
 */

namespace Drupal\integration\Tests\Consumer;

use Drupal\integration\Backend\Configuration\BackendConfiguration;
use Drupal\integration\Consumer\Configuration\ConsumerConfiguration;
use Drupal\integration\Consumer\Consumer;
use Drupal\integration\Tests\AbstractTest;

/**
 * Class ConsumerTest.
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

    /** @var Consumer $migration */
    $migration = Consumer::getInstance('test_configuration');

    $this->assertNotNull($migration);

    $mapping = $migration->getFieldMappings();
    foreach ($migration->getConfiguration()->getMapping() as $destination => $source) {
      $this->assertArrayHasKey($destination, $mapping);
      $this->assertEquals($source, $mapping[$destination]->getSourceField());
    }
    $this->assertArrayHasKey('title_field', $mapping);
    $this->assertEquals('title_field', $mapping['title_field']->getSourceField());
  }

}

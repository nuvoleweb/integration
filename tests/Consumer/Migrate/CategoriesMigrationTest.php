<?php

/**
 * @file
 * Contains CategoriesMigrationTest class.
 */

namespace Drupal\integration\Tests\Consumer\Migrate;

use Drupal\integration\Document\Document;

/**
 * Class CategoriesMigrationTest.
 *
 * @group migrate
 *
 * @package Drupal\integration\Tests\Consumer\Migrate
 */
class CategoriesMigrationTest extends AbstractMigrateTest {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    \Migration::getInstance('IntegrationTestCategories')->processImport();
  }

  /**
   * Testing Content migration.
   */
  public function testContentMigration() {
    /** @var \Migration $migration */
    $migration = \Migration::getInstance('IntegrationTestCategories');

    foreach ($this->fixtures['categories'] as $id => $fixture) {
      $mapping_row = $migration->getMap()->getRowBySource(['_id' => $id]);

      $raw_document = $this->getDocument('categories', $id);
      $source = new Document($raw_document);

      $taxonomy_term = taxonomy_term_load($mapping_row['destid1']);
      foreach (['en', 'fr'] as $language) {
        $source->setCurrentLanguage($language);

        // Assert that title has been imported correctly.
        $this->assertEquals($source->getFieldValue('name'), $taxonomy_term->name_field[$language][0]['value']);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown() {
    parent::tearDown();
    \Migration::getInstance('IntegrationTestCategories')->processRollback();
  }

}

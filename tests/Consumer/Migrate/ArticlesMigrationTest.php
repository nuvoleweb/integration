<?php

/**
 * @file
 * Contains ArticlesMigrationTest class.
 */

namespace Drupal\integration\Tests\Consumer\Migrate;

use Drupal\integration\Document\Document;

/**
 * Class ArticlesMigrationTest.
 *
 * @group migrate
 *
 * @package Drupal\integration\Tests\Consumer\Migrate
 */
class ArticlesMigrationTest extends AbstractMigrateTest {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    \Migration::getInstance('IntegrationTestNews')->processImport();
    \Migration::getInstance('IntegrationTestCategories')->processImport();
    \Migration::getInstance('IntegrationTestArticles')->processImport();
  }

  /**
   * Testing Content migration.
   */
  public function testContentMigration() {
    /** @var \Migration $migration */
    $migration = \Migration::getInstance('IntegrationTestArticles');

    foreach ($this->fixtures['articles'] as $id => $fixture) {
      $mapping_row = $migration->getMap()->getRowBySource(['_id' => $id]);

      $raw_document = $this->getDocument('articles', $id);
      $source = new Document($raw_document);

      $node = node_load($mapping_row['destid1']);
      foreach (['en', 'fr'] as $language) {
        $source->setCurrentLanguage($language);

        // Assert that title has been imported correctly.
        $this->assertEquals($source->getFieldValue('title'), $node->title_field[$language][0]['value']);

        // Assert that body has been imported correctly.
        $this->assertEquals($source->getFieldValue('abstract'), $node->body[$language][0]['value']);

        // Assert that list field has been imported correctly.
        foreach ($source->getFieldValue('list') as $key => $value) {
          $this->assertEquals($value, $node->field_integration_test_text[$language][$key]['value']);
        }

        // Assert that images are imported correctly.
        foreach ($source->getFieldValue('images') as $key => $value) {
          $this->assertContains($node->field_integration_test_images[$language][$key]['filename'], $value);
        }

        // Assert that image alt and title fields are imported correctly.
        foreach ($source->getFieldValue('image_alt_text') as $key => $value) {
          $this->assertEquals($value, $node->field_integration_test_images[$language][$key]['alt']);
          $this->assertEquals($value, $node->field_integration_test_images[$language][$key]['title']);
        }

        // Assert that files are imported correctly.
        foreach ($source->getFieldValue('files') as $key => $value) {
          $this->assertEquals($value, $node->field_integration_test_files[$language][$key]['filename']);
        }

        // Assert that date field has been imported correctly.
        $this->assertEquals($source->getFieldValue('date'), $node->field_integration_test_dates[LANGUAGE_NONE][0]['value']);
      }

      // Assert that taxonomy term reference migration worked successfully.
      $category_ids = $source->getFieldValue('categories');
      foreach ($node->field_integration_test_terms[LANGUAGE_NONE] as $key => $value) {
        $taxonomy_term_raw_document = $this->getDocument('categories', $category_ids[$key]);
        $taxonomy_term_source = new Document($taxonomy_term_raw_document);
        $taxonomy_term = taxonomy_term_load($value['tid']);
        $this->assertEquals($taxonomy_term_source->getFieldValue('name'), $taxonomy_term->name);
      }

      // Assert that entity reference field has been imported correctly.
      $news_ids = $source->getFieldValue('news');
      foreach ($node->field_integration_test_ref[LANGUAGE_NONE] as $key => $value) {
        $news_raw_document = $this->getDocument('news', $news_ids[$key]);
        $news_source = new Document($news_raw_document);
        $news_source->setCurrentLanguage('en');
        $news = node_load($value['target_id']);
        $this->assertEquals($news_source->getFieldValue('title'), $news->title_field['en'][0]['value']);
      }

      // Assert that default language has been imported correctly.
      $this->assertEquals($source->getDefaultLanguage(), $node->language);

      // Test pathauto integration.
      foreach (['en', 'fr'] as $language) {
        $alias = drupal_get_path_alias('node/' . $node->nid, $language);
        $this->assertNotFalse(strstr($alias, "-$language"));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown() {
    parent::tearDown();
    \Migration::getInstance('IntegrationTestNews')->processRollback();
    \Migration::getInstance('IntegrationTestArticles')->processRollback();
    \Migration::getInstance('IntegrationTestCategories')->processRollback();
  }

}

<?php

/**
 * @file
 * Contains DocumentTest.
 */

namespace Drupal\integration\Tests\Document;

use Drupal\integration\Document\Document;
use Drupal\integration\Tests\Consumer\Migrate\AbstractMigrateTest;

/**
 * Class DocumentTest.
 *
 * @package Drupal\integration\Tests\Consumer
 */
class DocumentTest extends AbstractMigrateTest {

  /**
   * Testing DocumentWrapperInterface methods.
   */
  public function testDocumentInterfaceMethods() {

    foreach ($this->fixtures['articles'] as $id => $filename) {

      $raw = $this->getDocument('articles', $id);
      $document = new Document($raw);

      // Get document ID.
      $this->assertEquals($document->getId(), $id);

      // Assert fields machine names.
      $machine_names = $document->getFieldMachineNames();
      foreach (['title', 'images', 'image_alt_text', 'abstract'] as $key) {
        $this->assertTrue(in_array($key, $machine_names));
      }

      // Assert fields values.
      $field_values = $document->getCurrentLanguageFieldsValues();
      foreach (['title', 'images', 'image_alt_text', 'abstract'] as $key) {
        $this->assertArrayHasKey($key, $field_values);
        $this->assertTrue(!empty($field_values[$key]));
      }

      // Assert default language.
      $this->assertEquals('en', $document->getDefaultLanguage());

      // Assert english title value.
      $this->assertContains('English title', $document->getFieldValue('title'));

      // Assert french title value.
      $document->setCurrentLanguage('fr');
      $this->assertEquals('fr', $document->getCurrentLanguage());
      $this->assertContains('French title', $document->getFieldValue('title'));

      // Assert available languages.
      $this->assertEquals(['fr', 'en'], $document->getAvailableLanguages());
    }
  }

  /**
   * Test empty field handling.
   */
  public function testEmptyFieldHandling() {

    $json = '{
      "default_language": "en",
      "languages": ["fr", "en"],
      "fields": {
        "field_name": {
          "en": "value",
          "fr": ""
        }
      }
    }';
    $raw = json_decode($json);
    $document = new Document($raw);
    $this->assertEmpty($document->getFieldValue('field_name', LANGUAGE_NONE));
    $this->assertEmpty($document->getFieldValue('field_name', 'it'));
    $this->assertEmpty($document->getFieldValue('field_name', 'fr'));
    $this->assertNotEmpty($document->getFieldValue('field_name', 'en'));

    $this->assertEquals('value', $document->getFieldValue('field_name'));
    $this->assertEquals('', $document->setCurrentLanguage('fr')->getFieldValue('field_name'));
  }

}

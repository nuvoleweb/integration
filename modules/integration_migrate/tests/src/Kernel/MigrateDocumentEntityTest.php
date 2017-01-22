<?php

namespace Drupal\integration_migrate\tests\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessage;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;

/**
 * Tests Migration of Documents using Integration.
 */
class MigrateDocumentEntityTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'system',
    'migrate',
    'migrate_plus',
    'integration',
    'integration_migrate',
    'language',
    'content_translation',
    'node',
    'field',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->installSchema('system', ['sequences']);
    $this->installSchema('node', array('node_access'));
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');

    // Create some languages.
    ConfigurableLanguage::createFromLangcode('en')->save();
    ConfigurableLanguage::createFromLangcode('fr')->save();

    // Create a content type.
    NodeType::create([
      'id' => 'integration_document_entity_test',
      'type' => 'integration_document_entity_test',
      'name' => 'Test node type',
    ])->save();

    /** @var \Drupal\content_translation\ContentTranslationManagerInterface $content_translation_manager */
    $content_translation_manager = \Drupal::service('content_translation.manager');

    $content_translation_manager->setEnabled('node', 'integration_document_entity_test', TRUE);
  }

  /**
   * Tests document import using migrate.
   */
  public function testSimpleDocumentImport() {
    $this->enableModules(['integration_migrate_entity']);

    $definition = [
      'source' => [
        'plugin' => 'integration_documents',
        'data_path' => drupal_get_path('module', 'integration_migrate_entity') . '/data/10861.json',
      ],
      'destination' => [
        'plugin' => 'integration_document',
      ],
    ];

    $migration = \Drupal::service('plugin.manager.migration')
      ->createStubMigration($definition);
    $executable = new MigrateExecutable($migration, new MigrateMessage());

    $result = $executable->import();
    $this->assertEquals(MigrationInterface::RESULT_COMPLETED, $result);

    /** @var \Drupal\node\NodeInterface $node */
    $node = Node::load(10861);

    // Check that we can load the node.
    $this->assertNotNull($node);

    // Check field data.
    $this->assertEquals($node->getTitle(), "Test simple document title");

    // Check metadata.
    $this->assertEquals($node->getType(), 'integration_document_entity_test');
    $this->assertEquals($node->getCreatedTime(), '1235583913');
    $this->assertEquals($node->getChangedTime(), '1329926433');
    $this->assertEquals($node->isPublished(), FALSE);
    $this->assertEquals($node->isSticky(), FALSE);
  }

  /**
   * Tests translated document import using migrate.
   *
   * It is ok to double test the properties already tested above, this way we
   * ensure data is stable.
   */
  public function testTranslatedDocumentImport() {
    $this->enableModules(['integration_migrate_entity']);

    $definition = [
      'source' => [
        'plugin' => 'integration_documents',
        'data_path' => drupal_get_path('module', 'integration_migrate_entity') . '/data/101337.json',
      ],
      'destination' => [
        'plugin' => 'integration_document',
      ],
    ];

    $migration = \Drupal::service('plugin.manager.migration')
      ->createStubMigration($definition);
    $executable = new MigrateExecutable($migration, new MigrateMessage());

    $result = $executable->import();
    $this->assertEquals(MigrationInterface::RESULT_COMPLETED, $result);

    /** @var \Drupal\node\NodeInterface $node */
    $node = Node::load(10861);

    // Check that we can load the node.
    $this->assertNotNull($node);

    // Check multilingual content.
    $node_translated = $node->getTranslation('fr');
    $this->assertNotEmpty($node_translated);

    // Check en field data.
    $this->assertEquals($node->getTitle(), "Test multilingual document title");

    // Check fr field data.
    $this->assertEquals($node_translated->getTitle(), 'Teste le titre du document multilingue');

    // Check metadata.
    $this->assertEquals($node->getType(), 'integration_document_entity_test');
    $this->assertEquals($node->getCreatedTime(), '1235583913');
    $this->assertEquals($node->getChangedTime(), '1329926433');
    $this->assertEquals($node->isPublished(), FALSE);
    $this->assertEquals($node->isSticky(), FALSE);

  }

}

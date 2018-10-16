<?php

namespace Drupal\Tests\integration_migrate\Kernel;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Tests\EntityReference\EntityReferenceTestTrait;
use Drupal\KernelTests\KernelTestBase;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessage;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;

/**
 * Tests Migration of Documents using Integration.
 *
 * @group integration_migrate
 */
class MigrateDocumentEntityTest extends KernelTestBase {

  use EntityReferenceTestTrait;

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
    'taxonomy',
    'content_translation',
    'integration_migrate_entity',
    'node',
    'field',
    'text',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->installSchema('system', ['sequences']);
    $this->installSchema('node', ['node_access']);
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installEntitySchema('taxonomy_term');

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
   * Tests document import using migrate without an id.
   */
  public function testSimpleDocumentImport() {
    $definition = [
      'source' => [
        'plugin' => 'integration_documents',
        'data_path' => drupal_get_path('module', 'integration_migrate_entity') . '/data/10861.json',
      ],
      'process' => [
        'created' => 'created',
        'changed' => 'changed',
        'status' => 'status',
        'title' => 'title',
      ],
      'destination' => [
        'plugin' => 'entity:node',
        'default_bundle' => 'integration_document_entity_test',
      ],
    ];

    /** @var MigrationInterface $migration */
    $migration = \Drupal::service('plugin.manager.migration')
      ->createStubMigration($definition);
    $executable = new MigrateExecutable($migration, new MigrateMessage());

    $result = $executable->import();
    $this->assertEquals(MigrationInterface::RESULT_COMPLETED, $result);

    /** @var \Drupal\node\NodeInterface $node */
    // @TODO: This may not be node 1.
    $node = Node::load(1);

    // Check that we can load the node.
    $this->assertNotNull($node);

    // Check field data.
    $this->assertEquals('Test simple document title', $node->getTitle());

    // Check metadata.
    $this->assertEquals('integration_document_entity_test', $node->getType());
    $this->assertEquals('1235583913', $node->getCreatedTime());
    $this->assertEquals('1329926433', $node->getChangedTime());
    $this->assertEquals(FALSE, $node->isPublished());
    $this->assertEquals(FALSE, $node->isSticky());
  }

  /**
   * Tests document import using migrate with an id.
   */
  public function testSimpleDocumentIdImport() {
    $definition = [
      'source' => [
        'plugin' => 'integration_documents',
        'data_path' => drupal_get_path('module', 'integration_migrate_entity') . '/data/10861.json',
      ],
      'process' => [
        'nid' => 'id',
        'created' => 'created',
        'changed' => 'changed',
        'status' => 'status',
        'title' => 'title',
      ],
      'destination' => [
        'plugin' => 'entity:node',
        'default_bundle' => 'integration_document_entity_test',
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
    $this->assertEquals('Test simple document title', $node->getTitle());

    // Check metadata.
    $this->assertEquals('integration_document_entity_test', $node->getType());
    $this->assertEquals('1235583913', $node->getCreatedTime());
    $this->assertEquals('1329926433', $node->getChangedTime());
    $this->assertEquals(FALSE, $node->isPublished());
    $this->assertEquals(FALSE, $node->isSticky());
  }

  /**
   * Tests translated document import using migrate.
   *
   * It is ok to double test the properties already tested above, this way we
   * ensure data is stable.
   */
  public function testTranslatedDocumentImport() {
    $definition = [
      'source' => [
        'plugin' => 'integration_documents',
        'data_path' => drupal_get_path('module', 'integration_migrate_entity') . '/data/101337.json',
      ],
      'process' => [
        'nid' => 'id',
        'created' => 'created',
        'changed' => 'changed',
        'title' => 'title',
        'default_langcode' => 'default_langcode',
      ],
      'destination' => [
        'plugin' => 'entity:node',
        'default_bundle' => 'integration_document_entity_test',
      ],
    ];

    $migration = \Drupal::service('plugin.manager.migration')
      ->createStubMigration($definition);
    $executable = new MigrateExecutable($migration, new MigrateMessage());

    $result = $executable->import();
    $this->assertEquals(MigrationInterface::RESULT_COMPLETED, $result);

    /** @var \Drupal\node\NodeInterface $node */
    $node = Node::load(101337);

    // Check that we can load the node.
    $this->assertNotNull($node);

    // Check multilingual content.
    $node_translated = $node->getTranslation('fr');
    $this->assertNotEmpty($node_translated);

    // Check en field data.
    $this->assertEquals('Test multilingual document title', $node->getTitle());

    // Check fr field data.
    $this->assertEquals('Teste le titre du document multilingue', $node_translated->getTitle());

    // Check metadata.
    $this->assertEquals('integration_document_entity_test', $node->getType());
    $this->assertEquals('1235583913', $node->getCreatedTime());
    $this->assertEquals('1329926433', $node->getChangedTime());
    $this->assertEquals(FALSE, $node->isPublished());
    $this->assertEquals(FALSE, $node->isSticky());
  }

  /**
   * Tests migrating a folder instead of a specific file.
   */
  public function testFolderDocumentImport() {
    $definition = [
      'source' => [
        'plugin' => 'integration_documents',
        'data_path' => drupal_get_path('module', 'integration_migrate_entity') . '/data',
      ],
      'process' => [
        'nid' => 'id',
        'title' => 'title',
      ],
      'destination' => [
        'plugin' => 'entity:node',
        'default_bundle' => 'integration_document_entity_test',
      ],
    ];

    $migration = \Drupal::service('plugin.manager.migration')
      ->createStubMigration($definition);
    $executable = new MigrateExecutable($migration, new MigrateMessage());

    $result = $executable->import();
    $this->assertEquals(MigrationInterface::RESULT_COMPLETED, $result);

    /** @var \Drupal\node\NodeInterface $node1 */
    $node1 = Node::load(10861);
    // Check that we can load the node.
    $this->assertNotNull($node1);
    $this->assertEquals('Test simple document title', $node1->getTitle());
    /** @var \Drupal\node\NodeInterface $node2 */
    $node2 = Node::load(101337);
    // Check that we can load the node.
    $this->assertNotNull($node2);
    $this->assertEquals('Test multilingual document title', $node2->getTitle());
  }

  /**
   * Tests that we can map taxonomy data.
   */
  public function testMappingTaxonomyDocumentImport() {
    // Create a vocabulary named "Tags".
    $vocabulary = Vocabulary::create([
      'name' => 'Tags',
      'vid' => 'tags',
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ]);
    $vocabulary->save();

    Term::create([
      'name' => 'Tag 1',
      'vid' => 'tags',
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ])->save();
    Term::create([
      'name' => 'Tag 2',
      'vid' => 'tags',
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ])->save();

    $handler_settings = [
      'target_bundles' => [
        $vocabulary->id() => $vocabulary->id(),
      ],
      // Enable auto-create.
      'auto_create' => TRUE,
    ];
    $this->createEntityReferenceField('node', 'integration_document_entity_test', 'field_tags', 'Tags', 'taxonomy_term', 'default', $handler_settings, 10);

    $definition = [
      'source' => [
        'plugin' => 'integration_documents',
        'data_path' => drupal_get_path('module', 'integration_migrate_entity') . '/data/10931.json',
      ],
      'process' => [
        'nid' => 'id',
        'title' => 'title',
        'field_tags' => [
          'plugin' => 'tag_to_id',
          'source' => 'tags',
        ],
      ],
      'destination' => [
        'plugin' => 'entity:node',
        'default_bundle' => 'integration_document_entity_test',
      ],
    ];

    $migration = \Drupal::service('plugin.manager.migration')
      ->createStubMigration($definition);
    $executable = new MigrateExecutable($migration, new MigrateMessage());

    $result = $executable->import();
    $this->assertEquals(MigrationInterface::RESULT_COMPLETED, $result);

    /** @var \Drupal\node\NodeInterface $node */
    $node = Node::load(10931);
    $this->assertNotNull($node);

    // Load the tags.
    $tags = $node->get('field_tags')->referencedEntities();

    // Check that the terms are referenced.
    $this->assertEquals('Tag 1', $tags[0]->getName());
    $this->assertEquals('Tag 2', $tags[1]->getName());
  }

  /**
   * Tests that we can process data with multiple rows using mapping.
   */
  public function testMappingMultiDataDocumentImport() {

    FieldStorageConfig::create([
      'field_name' => 'field_multi_data',
      'entity_type' => 'node',
      'type' => 'text',
    ])->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->save();

    FieldConfig::create([
      'field_name' => 'field_multi_data',
      'entity_type' => 'node',
      'bundle' => 'integration_document_entity_test',
    ])->save();

    $definition = [
      'source' => [
        'plugin' => 'integration_documents',
        'data_path' => drupal_get_path('module', 'integration_migrate_entity') . '/data/10931.json',
      ],
      'process' => [
        'nid' => 'id',
        'title' => 'title',
        'field_multi_data' => 'multi_data',
      ],
      'destination' => [
        'plugin' => 'entity:node',
        'default_bundle' => 'integration_document_entity_test',
      ],
    ];

    $migration = \Drupal::service('plugin.manager.migration')
      ->createStubMigration($definition);
    $executable = new MigrateExecutable($migration, new MigrateMessage());

    $result = $executable->import();
    $this->assertEquals(MigrationInterface::RESULT_COMPLETED, $result);

    /** @var \Drupal\node\NodeInterface $node */
    $node = Node::load(10931);
    $this->assertNotNull($node);

    // Check data in field_multi_data.
    $field_multi_data = $node->get('field_multi_data');
    $this->assertEquals('List item 1', $field_multi_data->get(0)->getValue()['value']);
    $this->assertEquals('List item 2', $field_multi_data->get(1)->getValue()['value']);
  }

}

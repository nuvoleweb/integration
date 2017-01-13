<?php

use Drupal\KernelTests\KernelTestBase;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessage;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;

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
    'integration_migrate',
    'language',
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
  }

  /**
   * Tests document import using migrate.
   */
  public function testDocumentImport() {
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

    $this->assertNotNull($node);
  }

}
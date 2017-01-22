<?php

namespace Drupal\integration_migrate\Plugin\migrate\destination;

use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
use Drupal\migrate\Row;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\Entity\EntityManagerInterface;

/**
 * Migration destination for documents.
 *
 * @MigrateDestination(
 *   id = "integration_document"
 * )
 */
class IntegrationDocument extends EntityContentBase {

  /**
   * The document.
   *
   * @var \Drupal\integration\Document\Document
   */
  protected $document;

  /**
   * The source plugin.
   *
   * @var \Drupal\migrate\Plugin\MigrateSourceInterface
   */
  private $sourcePlugin;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, EntityStorageInterface $storage, array $bundles, EntityManagerInterface $entity_manager, FieldTypePluginManagerInterface $field_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration, $storage, $bundles, $entity_manager, $field_type_manager);
    $this->sourcePlugin = $this->migration->getSourcePlugin();
    $this->document = $this->migration->getSourcePlugin()->getDocument();
  }

  /**
   * Get whether this destination is for translations.
   *
   * @return bool
   *   Whether this destination is for translations.
   */
  protected function isTranslationDestination() {
    return !empty($this->document->getAvailableLanguages());
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    // @todo: Dont know what to do here, entity targets can be dynamic..
    $ids['nid'] = $this->getDefinitionFromEntity('nid');

    if ($this->isTranslationDestination()) {
      if (!$langcode_key = $this->getKey('langcode')) {
        throw new MigrateException('This entity type does not support translation.');
      }
      $ids[$langcode_key] = $this->getDefinitionFromEntity($langcode_key);
    }

    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundle(Row $row) {
    return $this->sourcePlugin->getDocumentType();
  }

  /**
   * Returns a specific entity key.
   *
   * @param string $key
   *   The name of the entity key to return.
   *
   * @return string|bool
   *   The entity key, or FALSE if it does not exist.
   *
   * @see \Drupal\Core\Entity\EntityTypeInterface::getKeys()
   */
  protected function getKey($key) {
    return $this->storage->getEntityType()->getKey($key);
  }

  /**
   * Gets the definition from the entity.
   *
   * @param string $key
   *   The key to get.
   *
   * @return array
   *   The field definitions.
   */
  protected function getDefinitionFromEntity($key) {
    /** @var \Drupal\Core\Field\FieldStorageDefinitionInterface[] $definitions */
    $definitions = $this->entityManager->getBaseFieldDefinitions('node');
    $field_definition = $definitions[$key];

    return [
      'type' => $field_definition->getType(),
    ] + $field_definition->getSettings();
  }

  /**
   * Finds the entity type from configuration or plugin ID.
   *
   * @param string $plugin_id
   *   The plugin ID.
   *
   * @return string
   *   The entity type.
   */
  protected static function getEntityTypeId($plugin_id) {
    return 'node';
  }

}

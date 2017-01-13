<?php

namespace Drupal\integration_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Drupal\Tests\user\Kernel\TempStoreDatabaseTest;

/**
 * Source plugin for retrieving data via URLs.
 *
 * @MigrateSource(
 *   id = "integration_documents"
 * )
 */
class IntegrationDocuments extends SourcePluginBase {
  /**
   * The path to the json sources.
   *
   * @var string
   */
  private $dataPath;

  /**
   * Array of parsed documents.
   *
   * @var array
   */
  private $documentsArray = [];

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    $this->dataPath = $configuration['data_path'];
    $configuration['default_bundle'] = $this->getDocumentType();
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDocumentsArray() {
    if (empty($this->documentsArray)) {
      $document = json_decode(file_get_contents($this->dataPath), TRUE);
      $document['id'] = $document['_id'];
      $data[$document['_id']] = $document;
      $this->documentsArray = $data;
    }
    return $this->documentsArray;
  }

  /**
   * Gets the document.
   *
   * @return array
   *   The array of field data.
   */
  public function getDocument() {
    $documents = $this->getDocumentsArray();
    return reset($documents);
  }

  /**
   * Gets the document type.
   *
   * @return string
   *   The type as string.
   */
  public function getDocumentType() {
    return $this->getDocument()['type'];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $language = 'en';
    foreach ($row->getSourceProperty('fields') as $field_id => $field_data) {
      $row->setDestinationProperty($field_id, reset($field_data[$language]));
    }
    $row->setDestinationProperty('id', $row->getSourceProperty('id'));
    $row->setDestinationProperty('nid', $row->getSourceProperty('id'));
    $row->setSourceProperty('bundle', $row->getSourceProperty('type'));
    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'Integration documents';
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    // @todo: make dynamic
    return [
      'id' => [
        'type' => 'integer',
        'unsigned' => FALSE,
        'size' => 'big',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function initializeIterator() {
    return new \ArrayIterator($this->getDocumentsArray());
  }

  /**
   * Returns available fields on the source.
   *
   * @return array
   *   Available fields in the source, keys are the field machine names as used
   *   in field mappings, values are descriptions.
   */
  public function fields() {
    $b = 'f';
    // TODO: Implement fields() method.
  }
}
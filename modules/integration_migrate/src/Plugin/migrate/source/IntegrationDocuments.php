<?php

namespace Drupal\integration_migrate\Plugin\migrate\source;

use Drupal\integration\Document\Document;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;

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
      $document_raw = json_decode(file_get_contents($this->dataPath));
      $document = new Document($document_raw);

      // Add a row for each language.
      $this->documentsArray[$document->getId()] = [
        'id' => $document->getId(),
        'language' => $document->getDefaultLanguage(),
        'raw' => $document_raw,
        'processed' => $document,
      ];
    }
    return $this->documentsArray;
  }

  /**
   * Gets the document.
   *
   * @return Document
   *   The array of field data.
   */
  public function getDocument() {
    $documents = $this->getDocumentsArray();
    $document = reset($documents);
    return $document['processed'];
  }

  /**
   * Gets the document type.
   *
   * @return string
   *   The type as string.
   */
  public function getDocumentType() {
    return $this->getDocument()->getMetadata('type');
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $language = $row->getSource()['language'];

    foreach ($this->getDocument()->getFieldMachineNames() as $field_name) {
      $row->setDestinationProperty($field_name, $this->getDocument()
        ->getFieldValue($field_name, $language));
    }

    // @todo: Static metadata, this can go into Document I think..
    $static_metadata = [
      'nid' => '_id',
      'bundle' => 'type',
      'created' => 'created',
      'changed' => 'changed',
      'status' => 'status',
      'sticky' => 'sticky',
      'default_langcode' => 'default_langcode',
    ];

    foreach ($static_metadata as $destination => $source) {
      if (!is_null($this->getDocument()->getMetadata($source))) {
        $row->setDestinationProperty($destination, $this->getDocument()
          ->getMetadata($source));
      }
    }

    // We need the language property.
    $row->setDestinationProperty('language', $language);
    $row->setDestinationProperty('langcode', $language);

    $bar = $row->getIdMap();
    $bar['destid2'] = $language;
    $row->setIdMap($bar);

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
    // @todo: make dynamic?
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
    return parent::fields();
  }

}

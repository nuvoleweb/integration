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
   * Contains mapping data.
   *
   * @var array
   */
  private $mappingData;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    $this->dataPath = $configuration['data_path'];
    $configuration['default_bundle'] = $this->getDocumentType();
    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration
    );
  }

  /**
   * Gets the documents to migrate.
   *
   * @return array
   *   Array of documents.
   */
  protected function getDocumentsArray() {
    if (empty($this->documentsArray)) {
      if (is_dir($this->dataPath)) {
        foreach (file_scan_directory($this->dataPath, '/.*\.json$/') as $file) {
          $document_raw = json_decode(file_get_contents($file->uri));
          $document = new Document($document_raw);

          // Add a row for each language.
          $this->documentsArray[$document->getId()] = [
            '_id' => $document->getId(),
            'language' => $document->getDefaultLanguage(),
            'raw' => $document_raw,
            'processed' => $document,
          ];
        }
      }
      else {
        $document_raw = json_decode(file_get_contents($this->dataPath));
        $document = new Document($document_raw);

        // Add a row for each language.
        $this->documentsArray[$document->getId()] = [
          '_id' => $document->getId(),
          'language' => $document->getDefaultLanguage(),
          'raw' => $document_raw,
          'processed' => $document,
        ];
      }
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

    foreach ($this->getMappingData() as $destination => $source) {
      if (NULL !== $row->getSource()['processed']->getMetadata($source)) {
        $row->setSourceProperty(
          $destination,
          $row->getSource()['processed']->getMetadata($source)
        );
      }
    }

    // Map the remaining data, but exclude fields with custom mapping.
    foreach ($row->getSource()['processed']->getFieldMachineNames() as $field_name) {
      $source = $field_name;
      $destination = $field_name;
      // Exclude already mapped data.
      if (array_key_exists($field_name, $this->getMappingData())) {
        $destination = $this->getMappingData()[$field_name];
      }
      $row->setSourceProperty(
        $destination,
        $row->getSource()['processed']->getFieldValue($source, $language)
      );
    }

    return parent::prepareRow($row);
  }

  /**
   * Gets the mapping data as an array.
   *
   * @return array
   *   The mapping data destination=>source.
   */
  private function getMappingData() {
    if (empty($this->mappingData)) {
      $this->mappingData = [
        'bundle' => 'type',
        'created' => 'created',
        'changed' => 'changed',
        'status' => 'status',
        'sticky' => 'sticky',
        'default_langcode' => 'default_langcode',
      ];
    }
    return $this->mappingData;
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
    return [
      '_id' => [
        'type' => 'string',
        'is_ascii' => TRUE,
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
    return [];
  }

}

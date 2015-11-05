<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\FileSystemBackend.
 */

namespace Drupal\integration\Backend;

use Drupal\integration\Document\Document;
use Drupal\integration\Document\DocumentInterface;

/**
 * Class FileSystemBackend.
 *
 * @package Drupal\integration\Backend
 */
class FileSystemBackend extends AbstractBackend {

  /**
   * {@inheritdoc}
   */
  public function listDocuments($resource_schema, $max = 0) {
    $list = [];
    $path = $this->getResourceStorageDirectory($resource_schema);
    $extension = $this->getFormatterHandler()->getExtension();
    foreach (file_scan_directory($path, '/.*\.' . $extension . '$/') as $file) {
      $list[] = $file->name;
    }
    return $max <= 0 ? $list : array_chunk($list, $max);
  }

  /**
   * {@inheritdoc}
   */
  public function create($resource_schema, DocumentInterface $document) {
    $id = $this->getBackendContentId($document);
    $document->setMetadata('_id', $id);

    $path = $this->getResourceStorageDirectory($resource_schema);
    if (!is_dir($path)) {
      mkdir($path);
    }
    $filename = $this->getFilename($resource_schema, $id);
    file_put_contents($filename, $this->getFormatterHandler()->encode($document));
    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function read($resource_schema, $id) {
    $filename = $this->getFilename($resource_schema, $id);

    if (file_exists($filename)) {
      $raw = file_get_contents($filename);
      $document = $this->getFormatterHandler()->decode($raw);
      return new Document($document);
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function update($resource_schema, DocumentInterface $document) {
    return $this->create($resource_schema, $document);
  }

  /**
   * {@inheritdoc}
   */
  public function delete($resource_schema, $id) {
    $filename = $this->getFilename($resource_schema, $id);

    if (file_exists($filename)) {
      unlink($filename);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getBackendContentId(DocumentInterface $document) {
    return $document->getMetadata('producer_content_id');
  }

  /**
   * Return storage directory path for a given resource.
   *
   * @param string $resource_schema
   *    Resource schema machine name.
   *
   * @return string
   *    Storage directory path for a given resource.
   */
  private function getResourceStorageDirectory($resource_schema) {
    $base_path = $this->getConfiguration()->getPluginSetting('backend.path');
    $folder = $this->getConfiguration()->getPluginSetting("resource_schema.$resource_schema.folder");
    return $base_path . DIRECTORY_SEPARATOR . $folder;
  }

  /**
   * Get full path filename for current document ID.
   *
   * @param string $resource_schema
   *    Resource schema machine name.
   * @param string $id
   *    Document ID.
   *
   * @return string
   *    Full path filename for current document ID.
   */
  private function getFilename($resource_schema, $id) {
    $path = $this->getResourceStorageDirectory($resource_schema);
    return $path . DIRECTORY_SEPARATOR . $id . '.' . $this->getFormatterHandler()->getExtension();
  }

}

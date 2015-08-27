<?php

/**
 * @file
 * Contains \Drupal\integration\Producer\AbstractProducer.
 */

namespace Drupal\integration\Producer;

use Drupal\integration\Configuration\ConfigurableInterface;
use Drupal\integration\Document\DocumentInterface;
use Drupal\integration\Configuration\AbstractConfiguration;

/**
 * Class AbstractProducer.
 *
 * @package Drupal\integration\Producer
 */
abstract class AbstractProducer implements ProducerInterface, ConfigurableInterface {

  /**
   * Current schema version.
   */
  const SCHEMA_VERSION = 'v1';

  /**
   * Configuration object.
   *
   * @var Configuration\ProducerConfiguration
   */
  private $configuration;

  /**
   * Entity wrapper.
   *
   * @var EntityWrapper\EntityWrapper
   */
  private $entityWrapper = NULL;

  /**
   * Document handler instance.
   *
   * @var DocumentInterface
   */
  private $document = NULL;

  /**
   * List of field handler definitions keyed by field type.
   *
   * @see integration_producer_get_field_handlers()
   *
   * @var array[FieldHandlerInterface]
   */
  private $fieldHandlers = array();

  /**
   * Constructor.
   *
   * @param Configuration\ProducerConfiguration $configuration
   *    Configuration object.
   * @param EntityWrapper\EntityWrapper $entity_wrapper
   *    Entity object.
   * @param DocumentInterface $document
   *    Document object.
   */
  public function __construct(Configuration\ProducerConfiguration $configuration, EntityWrapper\EntityWrapper $entity_wrapper, DocumentInterface $document) {
    $this->setConfiguration($configuration);
    $this->entityWrapper = $entity_wrapper;
    $this->document = $document;
    $this->fieldHandlers = integration_producer_get_field_handler_info();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(AbstractConfiguration $configuration) {
    $this->configuration = $configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityWrapper() {
    return $this->entityWrapper;
  }

  /**
   * {@inheritdoc}
   */
  public function getDocument() {
    return $this->document;
  }

  /**
   * {@inheritdoc}
   */
  public function getProducerId() {
    return $this->getConfiguration()->getProducerId();
  }


  /**
   * Return field handler object given field name and language.
   *
   * @param string $field_name
   *    Field machine name.
   * @param string $language
   *    Field language.
   *
   * @return FieldHandlers\AbstractFieldHandler
   *    Field handler object.
   */
  protected function getFieldHandler($field_name, $language) {
    $field_info = field_info_field($field_name);
    $class = isset($this->fieldHandlers[$field_info['type']]) ? $this->fieldHandlers[$field_info['type']]['class'] : $this->fieldHandlers['default']['class'];
    return new $class($field_name, $language, $this->getEntityWrapper(), $this->getDocument());
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Set document metadata.
    $this->getDocument()->setMetadata('type', $this->getDocumentType());
    $this->getDocument()->setMetadata('producer', $this->getProducerId());
    $this->getDocument()->setMetadata('producer_content_id', $this->getProducerContentId());
    $this->getDocument()->setMetadata('created', $this->getDocumentCreationDate());
    $this->getDocument()->setMetadata('updated', $this->getDocumentUpdateDate());
    $this->getDocument()->setMetadata('version', self::SCHEMA_VERSION);

    // Set multilingual-related metadata.
    $this->getDocument()->setMetadata('languages', $this->getEntityWrapper()->getAvailableLanguages());
    $this->getDocument()->setMetadata('default_language', $this->getEntityWrapper()->getDefaultLanguage());

    // Set field values.
    foreach ($this->getEntityWrapper()->getAvailableLanguages() as $language) {
      foreach ($this->getEntityWrapper()->getFieldList() as $field_name) {
        $this->getFieldHandler($field_name, $language)->process();
      }
    }

    $entity_wrapper = $this->getEntityWrapper();
    $document = $this->getDocument();
    drupal_alter('integration_producer_document_build', $entity_wrapper, $document);
    return $document;
  }

}

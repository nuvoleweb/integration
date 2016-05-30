<?php

/**
 * @file
 * Contains \Drupal\integration_producer\AbstractProducer.
 */

namespace Drupal\integration_producer;

use Drupal\integration\Backend\BackendFactory;
use Drupal\integration\ConfigurablePluginInterface;
use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Document\Document;
use Drupal\integration\Document\DocumentInterface;
use Drupal\integration\Plugins\PluginManager;

/**
 * Class AbstractProducer.
 *
 * @package Drupal\integration_producer
 */
abstract class AbstractProducer implements ProducerInterface, ConfigurablePluginInterface {

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
  private $fieldHandlers = [];

  /**
   * AbstractProducer constructor.
   *
   * @param Configuration\ProducerConfiguration $configuration
   *    Configuration object.
   * @param EntityWrapper\EntityWrapper $entity_wrapper
   *    Entity object.
   * @param DocumentInterface $document
   *    Document object.
   */
  public function __construct(Configuration\ProducerConfiguration $configuration, EntityWrapper\EntityWrapper $entity_wrapper, DocumentInterface $document) {
    $manager = PluginManager::getInstance('producer');

    $this->setConfiguration($configuration);
    $this->entityWrapper = $entity_wrapper;
    $this->document = $document;
    $this->fieldHandlers = $manager->getComponentDefinitions('field_handler');
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
  public function build($entity) {
    $this->getEntityWrapper()->set($entity);

    // Set document metadata.
    $this->getDocument()->setMetadata('type', $this->getDocumentType());
    $this->getDocument()->setMetadata('producer', $this->getProducerId());
    $this->getDocument()->setMetadata('producer_content_id', $this->getProducerContentId());
    $this->getDocument()->setMetadata('created', $this->getDocumentCreationDate());
    $this->getDocument()->setMetadata('updated', $this->getDocumentUpdateDate());
    $this->getDocument()->setMetadata('version', 'v1');

    // Set multilingual-related metadata.
    $this->getDocument()->setMetadata('languages', $this->getEntityWrapper()->getAvailableLanguages());
    $this->getDocument()->setMetadata('default_language', $this->getEntityWrapper()->getDefaultLanguage());

    // Set entity properties and fields values.
    $mapping = $this->getConfiguration()->getPluginSetting('mapping');
    foreach (array_keys($mapping) as $field_name) {
      $info = $this->getEntityWrapper()->getPropertyInfo($field_name);
      foreach ($this->getEntityWrapper()->getAvailableLanguages() as $language) {
        if (!isset($info['field'])) {
          $this->getDocument()->setCurrentLanguage($language);
          $property_value = $this->getEntityWrapper()->{$field_name}->value();
          $this->getDocument()->setField($mapping[$field_name], $property_value);
        }
        else {
          $field_handler = $this->getFieldHandler($field_name, $language);
          $field_handler->setDestinationField($mapping[$field_name]);
          $field_handler->process();
        }
      }
    }

    $this->getDocument()->setCurrentLanguage($this->getEntityWrapper()->getDefaultLanguage());
    $document = $this->getDocument();
    drupal_alter('integration_producer_document_build', $this, $document);
    $this->resetDocument();
    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function push($entity) {
    $document = $this->build($entity);
    $resource_schema = $this->getConfiguration()->getResourceSchema();
    $backend = $this->getBackendInstance();
    $backend->create($resource_schema, $document);
    return $document;
  }

  /**
   * {@inheritdoc}
   */
  public function getBackend() {
    return $this->getConfiguration()->getBackend();
  }

  /**
   * {@inheritdoc}
   */
  public function setBackend($backend) {
    $this->getConfiguration()->setBackend($backend);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBackendInstance() {
    return BackendFactory::getInstance($this->getBackend());
  }

  /**
   * Set producer entity bundle.
   *
   * @param string $entity_bundle
   *    Entity bundle.
   *
   * @return $this
   */
  public function setEntityBundle($entity_bundle) {
    $this->getConfiguration()->setEntityBundle($entity_bundle);
    return $this;
  }

  /**
   * Set resource schema bundle.
   *
   * @param string $resource_schema
   *    Resource schema machine name.
   *
   * @return $this
   */
  public function setResourceSchema($resource_schema) {
    $this->getConfiguration()->setResourceSchema($resource_schema);
    return $this;
  }

  /**
   * Set field mapping.
   *
   * @param string $source
   *    Source field machine name.
   * @param string $destination
   *    Destination field machine name.
   *
   * @return $this
   */
  public function setMapping($source, $destination) {
    $this->getConfiguration()->setMapping($source, $destination);
    return $this;
  }

  /**
   * Reset internal document object to allow subsequent document building.
   */
  public function resetDocument() {
    $this->document = new Document();
  }

}

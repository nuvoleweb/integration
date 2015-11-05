<?php

/**
 * @file
 * Contains AbstractFieldHandler.
 */

namespace Drupal\integration_producer\FieldHandlers;

use Drupal\integration\Document\DocumentInterface;
use Drupal\integration_producer\EntityWrapper\EntityWrapper;

/**
 * Class AbstractFieldHandler.
 *
 * @package Drupal\integration_producer\FieldHandlers
 */
abstract class AbstractFieldHandler implements FieldHandlerInterface {

  /**
   * Field name currently being handled.
   *
   * @var string
   */
  protected $fieldName = NULL;

  /**
   * Language to extract values for.
   *
   * @var string
   */
  protected $language = NULL;

  /**
   * Current field's entity wrapped by EntityWrapper class.
   *
   * @var EntityWrapper
   */
  protected $entityWrapper = NULL;

  /**
   * Document handler instance.
   *
   * @var DocumentInterface
   */
  protected $document = NULL;

  /**
   * Current field info array.
   *
   * @see field_info_field()
   *
   * @var array
   */
  protected $fieldInfo = [];

  /**
   * AbstractFieldHandler constructor.
   *
   * Field handlers receives both the source entity and the target document,
   * each handler will be responsible to process field values (e.g exploding
   * them into "sub-values", polishing them, etc.) and to add them to the
   * document object by calling DocumentInterface::addFieldValue() into their
   * own FieldHandlerInterface::processField() implementation.
   *
   * Each new field handler implementation must extend AbstractFieldHandler
   * and provide its own FieldHandlerInterface::processField() implementation.
   * Field handlers should walk through field values by using the provided
   * FieldHandlerInterface::getFieldValues() method, which takes care of
   * straightening out field values inconsistencies (e.g. cardinality) and to
   * properly set the current field handler language.
   *
   * @param string $field_name
   *    Field name currently being handled.
   * @param string $language
   *    Language to extract values for.
   * @param EntityWrapper $entity_wrapper
   *    Current field's entity wrapped by EntityWrapper class.
   * @param DocumentInterface $document
   *    Target document object to attach the field to.
   */
  public function __construct($field_name, $language, EntityWrapper $entity_wrapper, DocumentInterface $document) {
    $this->language = $language;
    $this->fieldName = $field_name;
    $this->entityWrapper = $entity_wrapper;
    $this->document = $document;
    $this->fieldInfo = field_info_field($field_name);
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldValues() {
    $values = $this->getEntityWrapper()->getField($this->fieldName, $this->language);
    if ($values) {
      // Normalize single-value field to ease value processing.
      return ($this->fieldInfo['cardinality'] == 1) ? [$values] : $values;
    }
    else {
      // Set empty values for each of the field's columns.
      // Since fields will be exploded in self::processField() this ensure we
      // will always have fields set in the documents, even if empty.
      foreach (array_keys($this->fieldInfo['columns']) as $column) {
        $values[$column] = '';
      }
      return [$values];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function process() {
    $this->getDocument()->setCurrentLanguage($this->language);
    $this->processField();
    $this->getDocument()->setCurrentLanguage($this->getDocument()->getDefaultLanguage());
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

}

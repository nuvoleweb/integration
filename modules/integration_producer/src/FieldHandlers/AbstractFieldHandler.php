<?php

/**
 * @file
 * Contains AbstractFieldHandler.
 */

namespace Drupal\integration\Producer\FieldHandlers;

use Drupal\integration\Document\DocumentInterface;
use Drupal\integration\Producer\EntityWrapper\EntityWrapper;

/**
 * Class AbstractFieldHandler.
 *
 * @package Drupal\integration\Producer\FieldHandlers
 */
abstract class AbstractFieldHandler implements FieldHandlerInterface {

  /**
   * Field name the handler is instantiated with.
   *
   * @var string
   */
  protected $fieldName = NULL;

  /**
   * Language the handler is instantiated with.
   *
   * @var string
   */
  protected $language = NULL;

  /**
   * Entity wrapper.
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
  protected $fieldInfo = array();

  /**
   * Constructor.
   *
   * @param EntityWrapper $entity_wrapper
   *    Entity object.
   * @param DocumentInterface $document
   *    Document object.
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
      return ($this->fieldInfo['cardinality'] == 1) ? array($values) : $values;
    }
    else {
      // Set empty values for each of the field's columns.
      // Since fields will be exploded in self::processField() this ensure we
      // will always have fields set in the documents, even if empty.
      foreach (array_keys($this->fieldInfo['columns']) as $column) {
        $values[$column] = '';
      }
      return array($values);
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

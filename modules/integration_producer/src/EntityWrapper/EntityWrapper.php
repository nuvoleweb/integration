<?php

/**
 * @file
 * Contains EntityWrapper.
 */

namespace Drupal\integration_producer\EntityWrapper;

/**
 * Class EntityWrapper.
 *
 * @package Drupal\integration_producer\EntityWrapper
 */
class EntityWrapper extends \EntityDrupalWrapper implements EntityWrapperInterface {

  /**
   * Default Entity Wrapper date format.
   *
   * Date format can be overridden by setting the 'default_date_format' value
   * on the $info array, to be passed in the object constructor.
   *
   * @see parent::__construct()
   */
  const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';

  /**
   * Construct a new EntityDrupalWrapper object.
   *
   * @param string $type
   *   The type of the passed data.
   * @param object $data
   *   Optional. The entity to wrap or its identifier.
   * @param mixed $info
   *   Optional. Used internally to pass info about properties down the tree.
   */
  public function __construct($type, $data = NULL, $info = []) {
    parent::__construct($type, $data, $info);
    $this->setUp();
  }

  /**
   * {@inheritdoc}
   */
  public function isProperty($name) {
    return in_array($name, $this->getPropertyList());
  }

  /**
   * {@inheritdoc}
   */
  public function getProperty($name) {
    if ($this->isProperty($name)) {
      $info = $this->getPropertyInfo($name);
      switch ($info['type']) {

        // Format and return a date properties.
        case 'date':
          $format = isset($this->info['default_date_format']) ? $this->info['default_date_format'] : self::DEFAULT_DATE_FORMAT;
          return date($format, $this->get($name)->value());

        // By default simply return all other property types.
        default:
          return $this->get($name)->value();
      }
    }
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyList() {
    $properties = [];
    foreach ($this->propertyInfo['properties'] as $name => $info) {
      if (!isset($info['field'])) {
        $properties[] = $name;
      }
    }
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldList() {

    $fields = [];
    foreach ($this->propertyInfo['properties'] as $name => $info) {
      if (isset($info['field']) && $info['field']) {
        $fields[] = $name;
      }
    }
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function isField($name) {
    return in_array($name, $this->getFieldList());
  }

  /**
   * {@inheritdoc}
   */
  public function getField($name, $language = NULL) {
    $this->language($language);
    $value = $this->{$name}->value();
    $this->language($this->getDefaultLanguage());
    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableLanguages() {
    if (module_exists('entity_translation')) {
      $translations = entity_translation_get_handler($this->type, $this->data)->getTranslations();
      return $translations->data ? array_keys($translations->data) : [LANGUAGE_NONE];
    }
    return [LANGUAGE_NONE];
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultLanguage() {
    if (module_exists('entity_translation')) {
      $translation_handler = entity_translation_get_handler($this->type, $this->data);
      $translations = $translation_handler->getTranslations();
      return $translations->data ? $translation_handler->getLanguage() : LANGUAGE_NONE;
    }
    return LANGUAGE_NONE;
  }

}

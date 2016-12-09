<?php

/**
 * @file
 * Contains \Drupal\integration_migrate\AbstractMigration.
 */

namespace Drupal\integration_migrate;

/**
 * Class AbstractMigration.
 *
 * @method \MigrateDestinationEntity::getDestination()
 *
 * @package Drupal\integration_migrate
 *
 * Destination class implementing migration into translatable nodes.
 */
abstract class AbstractMigration extends \Migration {

  /**
   * Implements Migration::complete() callback.
   *
   * @param object $entity
   *    Entity object.
   * @param \stdClass $source_row
   *    Source row, as expected by Migrate class.
   */
  public function complete($entity, \stdClass $source_row) {
    $entity_type = $this->getDestination()->getEntityType();
    if (module_exists('entity_translation')) {
      if (entity_translation_enabled($entity_type)) {
        $this->saveTranslations($entity, $source_row);
      }
    }

    // Make sure that path aliases are generated for all available languages.
    // Only works if Migrate field "pathauto" is enabled.
    // Check PathautoMigrationHandler class for more information.
    if (module_exists('pathauto')) {
      $mappings = $this->getFieldMappings();
      if (isset($mappings['pathauto']) && $mappings['pathauto']->getDefaultValue()) {
        $function = "pathauto_{$entity_type}_update_alias";
        if (function_exists($function)) {
          foreach ($source_row->getAvailableLanguages() as $language) {
            $function($entity, 'update', ['language' => $language]);
          }
        }
      }
    }
  }

  /**
   * Save field translations for the specified entity.
   *
   * @param object $entity
   *    Entity object.
   * @param DocumentWrapperInterface $source_row
   *    Source row, as expected by Migrate class.
   */
  private function saveTranslations($entity, DocumentWrapperInterface $source_row) {

    foreach ($source_row->getAvailableLanguages() as $language) {

      if ($language != $source_row->getDefaultLanguage()) {

        $source_row->setSourceValues($language);
        $this->sourceValues = $source_row;
        $this->prepareRow($this->sourceValues);
        $this->applyMappings();

        // Prepare entity in order to correctly apply mappings in prepare().
        $new_entity = clone $entity;
        foreach ((array) $this->destinationValues as $field_name => $value) {
          $new_entity->$field_name = $value;
        }
        $this->getDestination()->prepare($new_entity, $this->sourceValues);

        $entity_type = $this->getDestination()->getEntityType();
        $bundle_name = $this->getDestination()->getBundle();

        $values = [];
        $field_instances = field_info_instances($entity_type, $bundle_name);
        foreach ($field_instances as $field_name => $field_instance) {
          if (isset($this->destinationValues->$field_name)) {
            $values[$field_name] = $new_entity->$field_name;
          }
        }

        // Apply translations, moving this into a separate method.
        $translation_handler = entity_translation_get_handler($entity_type, $entity);

        // Load translations.
        $translation_handler->loadTranslations();

        $translation = [
          'translate' => 0,
          'status' => TRUE,
          'language' => $language,
          'source' => $entity->translations->original,
          'changed' => time(),
        ];

        // Content based translation.
        if ($entity_type == 'node' && entity_translation_node_supported_type($entity->type)) {
          $translation['uid'] = $entity->uid;
          $translation['created'] = $entity->created;
          $translation['changed'] = $entity->changed;
        }

        // Add the new translation and store it.
        // @link https://www.drupal.org/node/1069774#comment-4127006
        $translation_handler->setTranslation($translation, $values);
        field_attach_update($entity_type, $entity);
      }
    }
  }

}

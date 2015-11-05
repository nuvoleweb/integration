<?php

/**
 * @file
 * Contains \Drupal\integration_consumer\Migrate\AbstractMigration.
 */

namespace Drupal\integration_consumer\Migrate;

/**
 * Class AbstractMigration.
 *
 * @package Drupal\integration_consumer\Migrate
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
    if (entity_translation_enabled($this->getDestination()->getEntityType())) {
      $this->saveTranslations($entity, $source_row);
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
          $translation['status'] = $entity->status;
          $translation['uid'] = $entity->uid;
          $translation['created'] = $entity->created;
          $translation['changed'] = $entity->changed;
        }

        // Add the new translation and store it.
        $translation_handler->setTranslation($translation, $values);

        // Preserve original language setting.
        $entity->field_language = $entity->language;
        $entity->language = $entity->translations->original;

        // Save entity.
        entity_save($entity_type, $entity);
      }
    }
  }

}

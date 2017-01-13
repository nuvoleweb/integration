<?php

/**
 * @file
 * Contains \Drupal\integration\Backend\Entity\BackendEntityController.
 */

namespace Drupal\integration\Backend\Entity;

/**
 * Class BackendEntityController.
 *
 * @package Drupal\integration\Backend\Entity
 */
class BackendEntityController extends \EntityAPIController {

  /**
   * Load entities by given conditions.
   *
   * @param string $backend_name
   *    Backend name.
   * @param string $backend_id
   *    Backend ID.
   * @param string $entity_type
   *    Entity type.
   * @param int $entity_id
   *    Entity ID.
   *
   * @return array
   *    Array of loaded entities matching the above criteria, if any.
   */
  public function loadByConditions($backend_name = NULL, $backend_id = NULL, $entity_type = NULL, $entity_id = NULL) {

    $query = new \EntityFieldQuery();
    $query->entityCondition('entity_type', 'integration_backend_entity');
    if ($backend_name) {
      $query->propertyCondition('backend_name', $backend_name);
    }
    if ($entity_type) {
      $query->propertyCondition('entity_type', $entity_type);
    }
    if ($entity_id) {
      $query->propertyCondition('entity_id', $entity_id);
    }
    $results = $query->execute();
    if (isset($results['integration_backend_entity']) && !empty($results['integration_backend_entity'])) {
      return $this->load(array_keys($results['integration_backend_entity']));
    }
    else {
      return [];
    }
  }

  /**
   * Load mapping entity based on its entity type and ID.
   *
   * @param string $entity_type
   *    Entity type.
   * @param int $entity_id
   *    Entity ID.
   *
   * @return object
   *    Fully loaded mapping entity.
   */
  public function loadByEntity($entity_type, $entity_id) {
    $results = $this->loadByConditions(NULL, NULL, $entity_type, $entity_id);
    return $results ? array_shift($results) : NULL;
  }

  /**
   * Delete mapping entity based on its entity type and ID.
   *
   * @param string $entity_type
   *    Entity type.
   * @param int $entity_id
   *    Entity ID.
   */
  public function deleteByEntity($entity_type, $entity_id) {
    if ($entity = $this->loadByEntity($entity_type, $entity_id)) {
      $this->delete([$entity->id]);
    }
  }

}

<?php

/**
 * @file
 * Contains Drupal\integration_consumer\AbstractConsumer.
 */

namespace Drupal\integration_consumer;

use Drupal\integration\Backend\BackendInterface;
use Drupal\integration\ConfigurablePluginInterface;
use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Plugins\PluginManager;
use Drupal\integration\Backend\Entity\BackendEntityController;
use Drupal\integration_consumer\Configuration\ConsumerConfiguration;
use Drupal\integration_consumer\MappingHandler\AbstractMappingHandler;
use Drupal\integration_consumer\Migrate\MigrateSourceBackend;
use Drupal\integration_migrate\AbstractMigration;

/**
 * Class AbstractConsumer.
 *
 * @package Drupal\integration_consumer
 */
abstract class AbstractConsumer extends AbstractMigration implements ConsumerInterface, ConfigurablePluginInterface {

  /**
   * Configuration object.
   *
   * @var ConsumerConfiguration
   */
  protected $configuration;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $arguments) {

    self::validateArguments($arguments);
    parent::__construct($arguments);

    /** @var ConsumerConfiguration $configuration */
    $configuration = ConfigurationFactory::load('integration_consumer', $arguments['consumer']['configuration']);
    $this->setConfiguration($configuration);

    $this->setMap($this->getMapInstance());

    $destination_class = $this->getDestinationClass();
    $destination = new $destination_class($configuration->getEntityBundle());
    $this->setDestination($destination);

    // Mapping default language is necessary for correct translation handling.
    $this->addFieldMapping('language', 'default_language');

    // Apply mapping.
    foreach ($this->getConfiguration()->getMapping() as $source => $destination) {
      $this->addFieldMapping($destination, $source);
      $this->processMappingHandlers($destination, $source);
    }

    // Set migration source backend and enable Migrate track_changes option,
    // in order to better handle updates.
    // See https://www.drupal.org/node/1835822
    $options = ['track_changes' => 1];
    $this->setSource(new MigrateSourceBackend($this, $options));
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceKey() {
    return [
      '_id' => [
        'type' => 'varchar',
        'length' => 255,
        'not null' => TRUE,
      ],
    ];
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
   * Set mapping given source and destination.
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
   * Set backend configuration machine name.
   *
   * @param string $backend
   *    Backend configuration machine name.
   *
   * @return $this
   */
  public function setBackend($backend) {
    $this->getConfiguration()->setBackend($backend);
    return $this;
  }

  /**
   * Set entity bundle machine name.
   *
   * @param string $entity_bundle
   *    Entity bundle machine name.
   *
   * @return $this
   */
  public function setEntityBundle($entity_bundle) {
    $this->getConfiguration()->setEntityBundle($entity_bundle);
    return $this;
  }

  /**
   * Set resource machine name.
   *
   * @param string $resource
   *    Resource machine name.
   *
   * @return $this
   */
  public function setResourceSchema($resource) {
    $this->getConfiguration()->setResourceSchema($resource);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function register($name) {
    $configuration = ConfigurationFactory::load('integration_consumer', $name);

    $arguments = [];
    $arguments['consumer']['configuration'] = $configuration->getMachineName();

    $plugin_manager = PluginManager::getInstance('consumer');
    $plugin = $configuration->getPlugin();

    self::validateArguments($arguments);
    \Migration::registerMigration($plugin_manager->getPlugin($plugin)->getClass(), $configuration->getMachineName(), $arguments);
  }

  /**
   * Register current consumer as Migrate migration and return its instance.
   *
   * @param string $machine_name
   *    Consumer configuration machine name.
   * @param string $class_name
   *    Deprecated: class name is retrieved from migrate_status.
   * @param array $arguments
   *    Deprecated: arguments are retrieved from migrate_status.
   *
   * @return AbstractConsumer
   *    Consumer object instance.
   */
  static public function getInstance($machine_name, $class_name = NULL, array $arguments = []) {
    self::register($machine_name);
    return parent::getInstance($machine_name, $class_name, $arguments);
  }

  /**
   * Process field mapping handlers.
   *
   * @param string $destination_field
   *    Destination field name.
   * @param string|null $source_field
   *    Source field name.
   */
  protected function processMappingHandlers($destination_field, $source_field) {
    $manager = PluginManager::getInstance('consumer');

    $handlers = $manager->getComponentDefinitions('mapping_handler');
    foreach ($handlers as $name => $info) {
      /** @var AbstractMappingHandler $handler */
      $handler = new $info['class']($this, $source_field, $destination_field);
      $handler->process();
    }
  }

  /**
   * Get map object instance depending on entity type setting.
   *
   * @return \MigrateMap
   *    Map object instance.
   */
  protected function getMapInstance() {
    $destination_class = $this->getDestinationClass();
    return new \MigrateSQLMap($this->getMachineName(), $this->getSourceKey(), $destination_class::getKeySchema());
  }

  /**
   * Make sure required arguments are present and valid.
   *
   * @param array $arguments
   *    Constructor's $arguments array.
   */
  static private function validateArguments(array $arguments) {

    if (!isset($arguments['consumer'])) {
      throw new \InvalidArgumentException(t('Consumer argument missing: "consumer".'));
    }
    if (!isset($arguments['consumer']['configuration'])) {
      throw new \InvalidArgumentException('Consumer sub-argument missing: "configuration".');
    }
  }

  /**
   * Consumer implementation of prepareRow().
   *
   * @param mixed $row
   *    Document wrapper object containing source data.
   *
   * @return bool
   *    TRUE to process this row, FALSE to have the source skip it.
   */
  public function prepareRow($row) {
    parent::prepareRow($row);
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getDestinationEntity($id) {
    $entity_type = $this->getDestinationEntityType();
    $mapping_row = $this->getMap()->getRowBySource(['_id' => $id]);
    if ($mapping_row && isset($mapping_row['destid1']) && !empty($mapping_row['destid1'])) {
      return entity_load_single($entity_type, $mapping_row['destid1']);
    }
    else {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDestinationEntityType() {
    $plugin_manager = PluginManager::getInstance('consumer');
    $plugin = $this->getConfiguration()->getPlugin();
    return $plugin_manager->getPlugin($plugin)->getEntityType();
  }

  /**
   * {@inheritdoc}
   */
  public function fetchAll() {
    $this->prepareUpdate();
    $this->processImport();
  }

  /**
   * {@inheritdoc}
   */
  public function complete($entity, \stdClass $source_row) {
    $controller = $this->getBackendEntityController();
    $entity_type = $this->getDestinationEntityType();
    $values = [
      'backend_name' => $this->getConfiguration()->getBackend(),
      'backend_id' => $source_row->getDocument()->getId(),
      'entity_type' => $entity_type,
      'entity_id' => entity_id($entity_type, $entity),
    ];
    if (!$controller->loadByEntity($values['entity_type'], $values['entity_id'])) {
      $backend_entity = entity_create('integration_backend_entity', $values);
      entity_save('integration_backend_entity', $backend_entity);
    }
    parent::complete($entity, $source_row);
  }

  /**
   * Remove backend-entity mapping after completing a rollback.
   *
   * @param array $ids
   *    Array of entity IDs that have been rolled-back.
   *
   * @see \MigrateDestinationEntity::completeRollback()
   */
  public function completeRollback($ids) {
    $entity_type = $this->getDestination()->getEntityType();
    foreach ($ids as $id) {
      $this->getBackendEntityController()->deleteByEntity($entity_type, $id);
    }
  }

  /**
   * Get backend entity controller.
   *
   * @return BackendEntityController
   *    Newly instantiated entity controller.
   */
  protected function getBackendEntityController() {
    return entity_get_controller('integration_backend_entity');
  }

  /**
   * {@inheritdoc}
   */
  public function preImport() {
    module_invoke_all('integration_consumer_migrate_pre_import', $this);
    parent::preImport();
  }

  /**
   * {@inheritdoc}
   */
  public function postImport() {
    parent::postImport();
    module_invoke_all('integration_consumer_migrate_post_import', $this);
  }

  /**
   * {@inheritdoc}
   */
  public function preRollback() {
    module_invoke_all('integration_consumer_migrate_pre_rollback', $this);
    parent::preRollback();
  }

  /**
   * {@inheritdoc}
   */
  public function postRollback() {
    parent::postRollback();
    module_invoke_all('integration_consumer_migrate_post_rollback', $this);
  }

  /**
   * Helper method for invoking given rule event.
   *
   * @param string $name
   *    Rules event name.
   */
  protected function invokeRulesEvent($name) {
    if (module_exists('rules')) {
      rules_invoke_event($name, $this->configuration);
    }
  }

  /**
   * Invoking Rules event.
   */
  public function invokePreImportEvent() {
    $this->invokeRulesEvent(self::RULES_EVENT_PRE_IMPORT);
  }

  /**
   * Invoking Rules event.
   */
  public function invokePostImportEvent() {
    $this->invokeRulesEvent(self::RULES_EVENT_POST_IMPORT);
  }

  /**
   * Invoking Rules event.
   */
  public function invokePreRollbackEvent() {
    $this->invokeRulesEvent(self::RULES_EVENT_PRE_ROLLBACK);
  }

  /**
   * Invoking Rules event.
   */
  public function invokePostRollbackEvent() {
    $this->invokeRulesEvent(self::RULES_EVENT_POST_ROLLBACK);
  }

}

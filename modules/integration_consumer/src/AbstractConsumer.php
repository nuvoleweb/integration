<?php

/**
 * @file
 * Contains Drupal\integration_consumer\AbstractConsumer.
 */

namespace Drupal\integration_consumer;

use Drupal\integration\Backend\BackendFactory;
use Drupal\integration\Backend\BackendInterface;
use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\ConfigurablePluginInterface;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\PluginManager;
use Drupal\integration_consumer\Configuration\ConsumerConfiguration;
use Drupal\integration_consumer\Migrate\AbstractMigration;
use Drupal\integration_consumer\MappingHandler\AbstractMappingHandler;
use Drupal\integration_consumer\Migrate\MigrateSourceBackend;

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
   * Backend object.
   *
   * @var BackendInterface
   */
  protected $backend;

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
    foreach ($this->getConfiguration()->getMapping() as $destination => $source) {
      $this->addFieldMapping($destination, $source);
      $this->processMappingHandlers($destination, $source);
    }

    // Set migration source backend.
    $backend = BackendFactory::getInstance($this->getConfiguration()->getBackend());
    $this->setSource(new MigrateSourceBackend($backend));
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceKey() {
    return array(
      '_id' => array(
        'type' => 'varchar',
        'length' => 255,
        'not null' => TRUE,
      ),
    );
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
  public static function register($name) {
    $configuration = ConfigurationFactory::load('integration_consumer', $name);

    $arguments = array();
    $arguments['consumer']['configuration'] = $configuration->getMachineName();

    $plugin_manager = PluginManager::getInstance('consumer');
    $plugin = $configuration->getPlugin();

    self::validateArguments($arguments);
    \Migration::registerMigration($plugin_manager->getClass($plugin), $configuration->getMachineName(), $arguments);
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
  static public function getInstance($machine_name, $class_name = NULL, array $arguments = array()) {
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
  protected function processMappingHandlers($destination_field, $source_field = NULL) {

    $handlers = integration_consumer_get_mapping_handler_info();
    foreach ($handlers as $name => $info) {
      /** @var AbstractMappingHandler $handler */
      $handler = new $info['class']($this);
      $handler->process($destination_field, $source_field);
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
    $mapping_row = $this->getMap()->getRowBySource(array('_id' => $id));
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
    return $plugin_manager->getEntityType($plugin);
  }

}

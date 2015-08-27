<?php

/**
 * @file
 * Contains Drupal\integration\Consumer\Consumer.
 */

namespace Drupal\integration\Consumer;

use Drupal\integration\Backend\BackendFactory;
use Drupal\integration\Backend\BackendInterface;
use Drupal\integration\Configuration\AbstractConfiguration;
use Drupal\integration\Configuration\ConfigurableInterface;
use Drupal\integration\Configuration\ConfigurationFactory;
use Drupal\integration\Consumer\Configuration\ConsumerConfiguration;
use Drupal\integration\Consumer\Migrate\AbstractMigration;
use Drupal\integration\Consumer\Migrate\DocumentWrapper;
use Drupal\integration\Consumer\Migrate\MigrateItemJSON;
use Drupal\integration\Consumer\Migrate\MigrateListJSON;
use Drupal\integration\Consumer\MappingHandler\AbstractMappingHandler;
use Drupal\integration\Consumer\Migrate\MigrateSourceBackend;

/**
 * Interface ConsumerInterface.
 *
 * @package Drupal\integration\Consumer
 */
class Consumer extends AbstractMigration implements ConsumerInterface, ConfigurableInterface {

  /**
   * List supported entity destinations so far. To be expanded soon.
   *
   * @var array
   */
  protected $supportedDestinations = array(
    'node' => '\MigrateDestinationNode',
    'taxonomy_term' => '\MigrateDestinationTerm',
  );

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
    $this->setDestination($this->getDestinationInstance());

    // Mapping default language is necessary for correct translation handling.
    $this->addFieldMapping('language', 'default_language');

    // @todo: Make the following an option set via UI.
    $this->addFieldMapping('promote')->defaultValue(FALSE);
    $this->addFieldMapping('status')->defaultValue(NODE_NOT_PUBLISHED);

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

    self::validateArguments($arguments);
    \Migration::registerMigration(__CLASS__, $configuration->getMachineName(), $arguments);
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
   * @return Consumer
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
    /** @var \MigrateDestinationNode $destination_class */
    $destination_class = $this->getDestinationClass();
    return new \MigrateSQLMap($this->getMachineName(), $this->getSourceKey(), $destination_class::getKeySchema());
  }

  /**
   * Get destination object instance depending on entity type setting.
   *
   * @return \MigrateDestination
   *    Destination object instance.
   */
  protected function getDestinationInstance() {
    $destination_class = $this->getDestinationClass();
    $bundle = $this->getConfiguration()->getEntityBundle();
    return new $destination_class($bundle);
  }

  /**
   * Return migration destination class depending on entity type setting.
   *
   * @return string
   *    Destination class name.
   */
  protected function getDestinationClass() {
    $entity_type = $this->getConfiguration()->getEntityType();
    if (isset($this->supportedDestinations[$entity_type])) {
      return $this->supportedDestinations[$entity_type];
    }
    throw new \InvalidArgumentException("Entity destination $entity_type not supported.");
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
    $entity_type = $this->getConfiguration()->getEntityType();
    $mapping_row = $this->getMap()->getRowBySource(array('_id' => $id));
    if ($mapping_row && isset($mapping_row['destid1']) && !empty($mapping_row['destid1'])) {
      return entity_load_single($entity_type, $mapping_row['destid1']);
    }
    else {
      return FALSE;
    }

  }

}

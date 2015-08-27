<?php

/**
 * @file
 * Contains IntegrationTestCategoriesMigration class.
 */

/**
 * Class IntegrationTestCategoriesMigration.
 */
class IntegrationTestCategoriesMigration extends IntegrationTestAbstractMigration {

  /**
   * {@inheritdoc}
   */
  public function __construct($arguments) {
    parent::__construct('categories', $arguments);

    $this->setMap(new MigrateSQLMap(
      $this->getMachineName(),
      $this->getSourceKey(),
      MigrateDestinationTerm::getKeySchema()
    ));
    $this->setDestination(new MigrateDestinationTerm('integration_test'));

    // Entity translation requires that both name fields are mapped.
    $this->addFieldMapping('name', 'name');
    $this->addFieldMapping('name_field', 'name');

    // Mapping default language is necessary for correct translation handling.
    $this->addFieldMapping('language', 'default_language');
  }

}

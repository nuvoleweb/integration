<?php

/**
 * @file
 * Contains IntegrationTestNewsMigration class.
 */

/**
 * Class IntegrationTestNewsMigration.
 */
class IntegrationTestNewsMigration extends IntegrationTestAbstractMigration {

  /**
   * {@inheritdoc}
   */
  public function __construct($arguments) {
    parent::__construct('news', $arguments);

    $this->setMap(new MigrateSQLMap(
      $this->getMachineName(),
      $this->getSourceKey(),
      MigrateDestinationNode::getKeySchema()
    ));
    $this->setDestination(new MigrateDestinationNode('integration_test'));

    // Entity translation requires that both title fields are mapped.
    $this->addFieldMapping('title', 'title');
    $this->addFieldMapping('title_field', 'title');
    $this->addFieldMapping('body', 'abstract');

    // Mapping default language is necessary for correct translation handling.
    $this->addFieldMapping('language', 'default_language');

    $this->addFieldMapping('promote')->defaultValue(FALSE);
    $this->addFieldMapping('status')->defaultValue(NODE_PUBLISHED);
  }

  /**
   * Process source row, so we can test if it works with translations.
   *
   * @param object $row
   *    Source row object.
   *
   * @return bool
   *    TRUE to process this row, FALSE to have the source skip it.
   */
  public function prepareRow($row) {
    $row->abstract = 'Processed ' . $row->abstract;
    return TRUE;
  }

}

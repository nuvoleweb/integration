<?php

/**
 * @file
 * Contains IntegrationTestArticlesMigration class.
 */

/**
 * Class IntegrationTestArticlesMigration.
 */
class IntegrationTestArticlesMigration extends IntegrationTestAbstractMigration {

  /**
   * {@inheritdoc}
   */
  public function __construct($arguments) {
    parent::__construct('articles', $arguments);

    // Requires taxonomy terms migration to run before content migration.
    $this->setHardDependencies([
      'IntegrationTestNews',
      'IntegrationTestCategories',
    ]);

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
    $this->addFieldMapping('field_integration_test_text', 'list');
    $this->addFieldMapping('field_integration_test_dates', 'date');

    // Using an external migration source requires source_type to be set to tid.
    $this->addFieldMapping('field_integration_test_terms', 'categories')->sourceMigration('IntegrationTestCategories');
    $this->addFieldMapping('field_integration_test_terms:source_type')->defaultValue('tid');

    // Entity reference field mapping.
    $this->addFieldMapping('field_integration_test_ref', 'news')->sourceMigration('IntegrationTestNews');

    // Import images.
    $this->addFieldMapping('field_integration_test_images', 'images');
    $this->addFieldMapping('field_integration_test_images:file_replace')->defaultValue(FILE_EXISTS_REPLACE);

    // Images title and alt text.
    $this->addFieldMapping('field_integration_test_images:alt', 'image_alt_text');
    $this->addFieldMapping('field_integration_test_images:title', 'image_alt_text');

    // Files.
    $this->addFieldMapping('field_integration_test_files', 'files');
    $this->addFieldMapping('field_integration_test_files:file_replace')->defaultValue(FILE_EXISTS_REPLACE);
    $this->addFieldMapping('field_integration_test_files:source_dir')->defaultValue(self::getFixturesPath() . '/files');

    // Mapping default language is necessary for correct translation handling.
    $this->addFieldMapping('language', 'default_language');

    $this->addFieldMapping('promote')->defaultValue(FALSE);
    $this->addFieldMapping('status')->defaultValue(NODE_PUBLISHED);

    // Test Pathauto integration.
    $this->addFieldMapping('pathauto')->defaultValue(1);
  }

}

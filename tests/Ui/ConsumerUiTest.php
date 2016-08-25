<?php

/**
 * @file
 * Contains \Drupal\integration\Tests\Ui\ConsumerUiTest
 */

namespace Drupal\integration\Tests\Ui;

use Drupal\integration_consumer\ConsumerFactory;

/**
 * Class ConsumerUiTest.
 *
 * @group ui
 * @group consumer
 *
 * @package Drupal\integration\Tests\Ui
 */
class ConsumerUiTest extends AbstractUiTest {

  /**
   * Administrators can access consumer administrative pages.
   */
  public function testCanAccessAdminPages() {
    $page = $this->getPage();
    $this->loginAs('administrator');
    $this->visit('admin/config/integration');

    $this->assertTrue($page->hasContent('Integration layer'));
    $this->assertTrue($page->hasLink('Consumers'));

    $page->clickLink('Consumers');
    $this->assertTrue($page->hasLink('Add consumer'));
    $this->assertTrue($page->hasLink('Import consumer'));

    $page->clickLink('Add consumer');
    $this->assertEquals('Add consumer', $page->find('css', 'h1')->getText());
  }

  /**
   * Administrators can create consumer.
   */
  public function testCreateConsumer() {
    $resource_schema = $this->createResourceSchema();
    $backend = $this->createBackend();

    $page = $this->getPage();
    $this->loginAs('administrator');
    $this->visit('admin/config/integration/consumer/add');

    $this->assertNoErrorMessages();
    $this->assertFalse($page->hasContent('Backend'));
    $this->assertFalse($page->hasContent('Resource schema'));

    $name = 'Consumer ' . rand();
    $machine_name = 'consumer_' . rand();

    $page->fillField('name', $name);
    $page->fillField('machine_name', $machine_name);
    $page->selectFieldOption('plugin', 'node_consumer');
    $this->pressButton('Select plugin');
    $this->assertTrue($page->hasContent('Entity bundle'));

    $page->selectFieldOption('entity_bundle', 'article');
    $this->pressButton('Select bundle');
    $this->assertTrue($page->hasContent('Backend'));
    $this->assertTrue($page->hasContent('Resource schema'));

    $page->selectFieldOption('backend', $backend->getMachineName());
    $this->pressButton('Select Backend');

    $page->selectFieldOption('resource', $resource_schema->getMachineName());
    $this->pressButton('Select resource schema');

    $this->assertTrue($page->hasSelect('source'));
    $this->assertTrue($page->hasSelect('destination'));

    $select = $page->find('named', ['select', 'source']);
    $this->assertNotNull($select);

    $option = $select->find('named', ['option', 'title']);
    $this->assertNotNull($option);

    $page->selectFieldOption('source', 'title');
    $page->selectFieldOption('destination', 'title');
    $this->pressButton('Add mapping');

    $this->assertTrue($page->hasContent('title'));
    $this->assertTrue($page->hasContent('Property: Title (title)'));

    $this->pressButton('Save');

    $this->assertEquals('Consumers', $page->find('css', 'h1')->getText());
    $this->assertTrue($page->hasContent('Configuration has been saved.'));
    $this->assertTrue($page->hasContent($name));

    $configuration = ConsumerFactory::loadConfiguration($machine_name);
    $this->assertEquals($name, $configuration->getName());
    $this->assertEquals($machine_name, $configuration->getMachineName());

    // Delete configuration via UI.
    $this->visit("admin/config/integration/consumer/manage/$machine_name/delete");
    $this->pressButton('Confirm');
    $this->assertTrue($page->hasContent("Deleted Consumer $name."));

    // Assert that the configuration is actually gone.
    $this->visit('admin/config/integration/consumer');
    $this->assertFalse($page->hasContent($name));
  }

}

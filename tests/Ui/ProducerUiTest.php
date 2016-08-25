<?php

/**
 * @file
 * Contains \Drupal\integration\Tests\Ui\ProducerUiTest
 */

namespace Drupal\integration\Tests\Ui;

use Drupal\integration_producer\ProducerFactory;

/**
 * Class ProducerUiTest.
 *
 * @group ui
 * @group producer
 *
 * @package Drupal\integration\Tests\Ui
 */
class ProducerUiTest extends AbstractUiTest {

  /**
   * Administrators can access producer administrative pages.
   */
  public function testCanAccessAdminPages() {
    $page = $this->getPage();
    $this->loginAs('administrator');
    $this->visit('admin/config/integration');

    $this->assertTrue($page->hasContent('Integration layer'));
    $this->assertTrue($page->hasLink('Producers'));

    $page->clickLink('Producers');
    $this->assertTrue($page->hasLink('Add producer'));
    $this->assertTrue($page->hasLink('Import producer'));

    $page->clickLink('Add producer');
    $this->assertEquals('Add producer', $page->find('css', 'h1')->getText());
  }

  /**
   * Administrators can create producer.
   */
  public function testCreateProducer() {
    $resource_schema = $this->createResourceSchema();
    $backend = $this->createBackend();

    $page = $this->getPage();
    $this->loginAs('administrator');
    $this->visit('admin/config/integration/producer/add');

    $this->assertNoErrorMessages();
    $this->assertFalse($page->hasContent('Backend'));
    $this->assertFalse($page->hasContent('Resource schema'));

    $name = 'Producer ' . rand();
    $machine_name = 'producer_' . rand();

    $page->fillField('name', $name);
    $page->fillField('machine_name', $machine_name);
    $page->selectFieldOption('plugin', 'node_producer');
    $this->pressButton('Select plugin');
    $this->assertTrue($page->hasContent('Entity bundle'));

    $page->selectFieldOption('entity_bundle', 'article');
    $this->pressButton('Select bundle');
    $this->assertTrue($page->hasContent('Resource schema'));

    $page->selectFieldOption('resource', $resource_schema->getMachineName());
    $this->pressButton('Select resource schema');

    $this->assertTrue($page->hasSelect('destination'));
    $this->assertTrue($page->hasSelect('source'));

    $select = $page->find('named', ['select', 'destination']);
    $this->assertNotNull($select);

    $option = $select->find('named', ['option', 'title']);
    $this->assertNotNull($option);

    $page->selectFieldOption('source', 'title');
    $page->selectFieldOption('destination', 'title');
    $this->pressButton('Add mapping');

    $this->assertTrue($page->hasContent('title'));
    $this->assertTrue($page->hasContent('Property: Title (title)'));

    $this->pressButton('Save');

    $this->assertEquals('Producers', $page->find('css', 'h1')->getText());
    $this->assertTrue($page->hasContent('Configuration has been saved.'));
    $this->assertTrue($page->hasContent($name));

    $configuration = ProducerFactory::loadConfiguration($machine_name);
    $this->assertEquals($name, $configuration->getName());
    $this->assertEquals($machine_name, $configuration->getMachineName());

    // Delete configuration via UI.
    $this->visit("admin/config/integration/producer/manage/$machine_name/delete");
    $this->pressButton('Confirm');
    $this->assertTrue($page->hasContent("Deleted Producer $name."));

    // Assert that the configuration is actually gone.
    $this->visit('admin/config/integration/producer');
    $this->assertFalse($page->hasContent($name));
  }

}

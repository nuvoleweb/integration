<?php

/**
 * @file
 * Contains \Drupal\integration\Tests\Ui\ResourceSchemaUiTest
 */

namespace Drupal\integration\Tests\Ui;

use Drupal\integration\ResourceSchema\ResourceSchemaFactory;

/**
 * Class ResourceSchemaUiTest.
 *
 * @group ui
 * @group resource_schema
 *
 * @package Drupal\integration\Tests\Ui
 */
class ResourceSchemaUiTest extends AbstractUiTest {

  /**
   * Administrators can access resource schema administrative pages.
   */
  public function testCanAccessAdminPages() {
    $page = $this->getPage();
    $this->loginAs('administrator');
    $this->visit('admin/config/integration');

    $this->assertTrue($page->hasContent('Integration layer'));
    $this->assertTrue($page->hasLink('Resource schemas'));

    $page->clickLink('Resource schemas');
    $this->assertTrue($page->hasLink('Add resource schema'));
    $this->assertTrue($page->hasLink('Import resource schema'));

    // Initial interface must provide name text field and plugin selection.
    $page->clickLink('Add resource schema');
    $this->assertEquals('Add resource schema', $page->find('css', 'h1')->getText());
  }

  /**
   * Administrators can create resource schemas.
   */
  public function testCreateResourceSchema() {
    $page = $this->getPage();
    $this->loginAs('administrator');
    $this->visit('admin/config/integration/resource-schema/add');

    $name = 'Resource schema ' . rand();
    $machine_name = 'resource_schema_' . rand();

    $page->fillField('name', $name);
    $page->fillField('machine_name', $machine_name);
    $page->selectFieldOption('plugin', 'raw_resource_schema');
    $this->pressButton('Select plugin');

    $this->assertTrue($page->hasContent('Field name'));
    $this->assertTrue($page->hasContent('Field label'));

    $page->fillField('field_name', 'field_1');
    $page->fillField('field_label', 'Field 1');
    $this->pressButton('Add');

    $page->fillField('field_name', 'field_2');
    $page->fillField('field_label', 'Field 2');
    $this->pressButton('Add');

    $this->assertTrue($page->hasContent('Field 1'));
    $this->assertTrue($page->hasContent('Field 2'));

    $this->pressButton('Save');

    $this->assertEquals('Resource schemas', $page->find('css', 'h1')->getText());
    $this->assertTrue($page->hasContent('Configuration has been saved.'));
    $this->assertTrue($page->hasContent($name));

    $configuration = ResourceSchemaFactory::loadConfiguration($machine_name);
    $this->assertEquals($name, $configuration->getName());
    $this->assertEquals($machine_name, $configuration->getMachineName());

    // Delete configuration via UI.
    $this->visit("admin/config/integration/resource-schema/manage/$machine_name/delete");
    $this->pressButton('Confirm');
    $this->assertTrue($page->hasContent("Deleted Resource schema $name."));

    // Assert that the configuration is actually gone.
    $this->visit('admin/config/integration/resource-schema');
    $this->assertFalse($page->hasContent($name));
  }

}

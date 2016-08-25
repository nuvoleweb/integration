<?php

/**
 * @file
 * Contains \Drupal\integration\Tests\Ui\BackendUiTest
 */

namespace Drupal\integration\Tests\Ui;

/**
 * Class BackendUiTest.
 *
 * @group ui
 * @group backend
 *
 * @package Drupal\integration\Tests\Ui
 */
class BackendUiTest extends AbstractUiTest {

  /**
   * Administrators can access backends administrative pages.
   */
  public function testCanAccessAdminPages() {
    $page = $this->getPage();
    $this->loginAs('administrator');
    $this->visit('admin/config/integration');

    $this->assertTrue($page->hasContent('Integration layer'));
    $this->assertTrue($page->hasLink('Backends'));

    $page->clickLink('Backends');
    $this->assertTrue($page->hasLink('Add backend'));
    $this->assertTrue($page->hasLink('Import backend'));

    // Initial interface must provide name text field and plugin selection.
    $page->clickLink('Add backend');
    $this->assertEquals('Add backend', $page->find('css', 'h1')->getText());
  }

  /**
   * Administrators can create backends.
   */
  public function testCreateBackend() {
    $page = $this->getPage();
    $this->loginAs('administrator');
    $this->visit('admin/config/integration/backend/add');

    // Backend requires at least a resource schema to be available.
    $this->assertTrue($page->hasContent('No resource schemas found. Add a resource schema before proceeding.'));

    // After creating a resource schema warning message should be gone.
    $this->createResourceSchema();
    $this->visit('admin/config/integration/backend/add');
    $this->assertNoErrorMessages();
  }

}

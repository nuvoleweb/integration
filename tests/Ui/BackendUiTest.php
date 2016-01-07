<?php

/**
 * @file
 * Contains \Drupal\integration\Tests\Ui\BackendUiTest
 */

namespace Drupal\integration\Tests\Ui;

/**
 * Class BackendUiTest.
 *
 * @package Drupal\integration\Tests\Ui
 */
class BackendUiTest extends AbstractUiTest {

  /**
   * Test administrators can manage backends.
   */
  public function testManageBackend() {
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
    $this->assertTrue($page->hasField('name'));
    $this->assertTrue($page->hasSelect('plugin'));

    $page->pressButton('Select plugin');
    $this->assertTrue($page->hasContent('Resource schemas'));
  }

}

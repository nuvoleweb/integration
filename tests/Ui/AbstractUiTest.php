<?php

/**
 * @file
 * Contains \Drupal\integration\Tests\Ui\AbstractUiTest
 */

namespace Drupal\integration\Tests\Ui;

use aik099\PHPUnit\BrowserTestCase;
use Drupal\Driver\DrupalDriver;

/**
 * Class AbstractUiTest.
 *
 * @package Drupal\integration\Tests\Ui
 */
class AbstractUiTest extends BrowserTestCase {

  /**
   * Drupal driver instance.
   *
   * @var DrupalDriver;
   */
  protected $driver;

  /**
   * List of users created during test execution.
   *
   * @var array[\stdClass]
   */
  protected $users = [];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {

    // Create a browser based on Goutte driver.
    $browser = $this->createBrowserConfiguration(['driver' => 'goutte']);
    $this->setBrowser($browser);

    // Setup Drupal driver.
    $this->driver = new DrupalDriver(DRUPAL_ROOT, BASE_URL);
    $this->driver->setCoreFromVersion();

    parent::setUp();
  }

  /**
   * {@inheritdoc}
   */
  protected function tearDown() {

    // Remove users created during test execution.
    foreach ($this->users as $user) {
      user_delete($user->uid);
    }
    parent::tearDown();
  }

  /**
   * Visit a Drupal path relative to BASE_URL set in phpunit.xml.
   *
   * @param string $url
   *    Drupal path to visit.
   */
  public function visit($url = NULL) {
    $session = $this->getSession();
    $session->visit(BASE_URL . '/' . $url);
  }

  /**
   * Create and return a user with a specific role, if any.
   *
   * @param string $role
   *    Role name, such as "administrator".
   *
   * @return \stdClass
   *    User object.
   */
  public function createUser($role = '') {
    $user = new \stdClass();
    $user->name = 'user-' . rand();
    $user->mail = $user->name . '@example.com';
    $user->pass = rand();
    $this->driver->userCreate($user);

    if ($role) {
      $this->driver->userAddRole($user, $role);
    }
    $this->users[$user->name] = $user;
    return $user;
  }

  /**
   * Create and login as a user with a specified role.
   *
   * @param string $role
   *    Role name, such as "administrator".
   */
  public function loginAs($role) {
    $page = $this->getSession()->getPage();
    $user = $this->createUser('administrator');
    $this->visit('user');
    $page->fillField('name', $user->name);
    $page->fillField('pass', $user->pass);
    $page->pressButton('Log in');
  }

  /**
   * Get current session's page.
   *
   * @return \Behat\Mink\Element\DocumentElement
   *    Return Mink page element.
   */
  public function getPage() {
    return $this->getSession()->getPage();
  }

}

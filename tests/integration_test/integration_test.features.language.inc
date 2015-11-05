<?php
/**
 * @file
 * integration_test.features.language.inc
 */

/**
 * Implements hook_locale_default_languages().
 */
function integration_test_locale_default_languages() {
  $languages = [];

  // Exported language: en.
  $languages['en'] = [
    'language' => 'en',
    'name' => 'English',
    'native' => 'English',
    'direction' => 0,
    'enabled' => 1,
    'plurals' => 0,
    'formula' => '',
    'domain' => '',
    'prefix' => '',
    'weight' => 0,
  ];
  // Exported language: fr.
  $languages['fr'] = [
    'language' => 'fr',
    'name' => 'French',
    'native' => 'Français',
    'direction' => 0,
    'enabled' => 1,
    'plurals' => 0,
    'formula' => '',
    'domain' => '',
    'prefix' => 'fr',
    'weight' => 0,
  ];
  return $languages;
}

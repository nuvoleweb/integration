<?php

/**
 * @file
 * Contains theme-related hook implementations.
 */

/**
 * Implements hook_theme().
 */
function integration_ui_theme() {
  return [
    'integration_form_table' => [
      'render element' => 'form',
    ],
  ];
}

/**
 * Theme callback for the form table.
 *
 * @see integration_ui_theme()
 * @link http://e9p.net/theming-drupal-7-form-elements-table
 */
function theme_integration_form_table(&$variables) {
  $form = $variables['form'];
  $rows = $form['rows'];
  $header = $form['#header'];

  $content = [
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => [],
  ];

  foreach (element_children($rows) as $row_index) {
    $row = [];
    foreach (element_children($rows[$row_index]) as $col_index) {
      $row[] = drupal_render($rows[$row_index][$col_index]);
    }
    $content['#rows'][] = $row;
  }
  return drupal_render($content);
}

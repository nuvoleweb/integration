<?php
/**
 * @file
 * integration_test.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function integration_test_field_default_field_bases() {
  $field_bases = [];

  // Exported field_base: 'body'
  $field_bases['body'] = [
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => [
      0 => 'node',
    ],
    'field_name' => 'body',
    'indexes' => [
      'format' => [
        0 => 'format',
      ],
    ],
    'locked' => 0,
    'module' => 'text',
    'settings' => [
      'entity_translation_sync' => FALSE,
    ],
    'translatable' => 1,
    'type' => 'text_with_summary',
  ];

  // Exported field_base: 'field_integration_test_dates'
  $field_bases['field_integration_test_dates'] = [
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => [],
    'field_name' => 'field_integration_test_dates',
    'indexes' => [],
    'locked' => 0,
    'module' => 'date',
    'settings' => [
      'cache_count' => 4,
      'cache_enabled' => 0,
      'entity_translation_sync' => FALSE,
      'granularity' => [
        'day' => 'day',
        'hour' => 'hour',
        'minute' => 'minute',
        'month' => 'month',
        'second' => 0,
        'year' => 'year',
      ],
      'timezone_db' => 'UTC',
      'todate' => 'optional',
      'tz_handling' => 'site',
    ],
    'translatable' => 0,
    'type' => 'datetime',
  ];

  // Exported field_base: 'field_integration_test_files'
  $field_bases['field_integration_test_files'] = [
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => [],
    'field_name' => 'field_integration_test_files',
    'indexes' => [
      'fid' => [
        0 => 'fid',
      ],
    ],
    'locked' => 0,
    'module' => 'file',
    'settings' => [
      'display_default' => 0,
      'display_field' => 0,
      'entity_translation_sync' => [
        0 => 'fid',
      ],
      'uri_scheme' => 'public',
    ],
    'translatable' => 1,
    'type' => 'file',
  ];

  // Exported field_base: 'field_integration_test_images'
  $field_bases['field_integration_test_images'] = [
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => [],
    'field_name' => 'field_integration_test_images',
    'indexes' => [
      'fid' => [
        0 => 'fid',
      ],
    ],
    'locked' => 0,
    'module' => 'image',
    'settings' => [
      'default_image' => 0,
      'entity_translation_sync' => [
        0 => 'fid',
      ],
      'uri_scheme' => 'public',
    ],
    'translatable' => 1,
    'type' => 'image',
  ];

  // Exported field_base: 'field_integration_test_ref'
  $field_bases['field_integration_test_ref'] = [
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => [],
    'field_name' => 'field_integration_test_ref',
    'indexes' => [
      'target_id' => [
        0 => 'target_id',
      ],
    ],
    'locked' => 0,
    'module' => 'entityreference',
    'settings' => [
      'entity_translation_sync' => FALSE,
      'handler' => 'base',
      'handler_settings' => [
        'sort' => [
          'type' => 'none',
        ],
        'target_bundles' => [
          'integration_test' => 'integration_test',
        ],
      ],
      'target_type' => 'node',
    ],
    'translatable' => 0,
    'type' => 'entityreference',
  ];

  // Exported field_base: 'field_integration_test_terms'
  $field_bases['field_integration_test_terms'] = [
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => [],
    'field_name' => 'field_integration_test_terms',
    'indexes' => [
      'tid' => [
        0 => 'tid',
      ],
    ],
    'locked' => 0,
    'module' => 'taxonomy',
    'settings' => [
      'allowed_values' => [
        0 => [
          'vocabulary' => 'integration_test',
          'parent' => 0,
        ],
      ],
      'entity_translation_sync' => FALSE,
      'options_list_callback' => 'title_taxonomy_allowed_values',
    ],
    'translatable' => 0,
    'type' => 'taxonomy_term_reference',
  ];

  // Exported field_base: 'field_integration_test_text'
  $field_bases['field_integration_test_text'] = [
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => [],
    'field_name' => 'field_integration_test_text',
    'indexes' => [
      'format' => [
        0 => 'format',
      ],
    ],
    'locked' => 0,
    'module' => 'text',
    'settings' => [
      'entity_translation_sync' => FALSE,
    ],
    'translatable' => 1,
    'type' => 'text_long',
  ];

  // Exported field_base: 'name_field'
  $field_bases['name_field'] = [
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => [],
    'field_name' => 'name_field',
    'indexes' => [
      'format' => [
        0 => 'format',
      ],
    ],
    'locked' => 0,
    'module' => 'text',
    'settings' => [
      'entity_translation_sync' => FALSE,
      'max_length' => 255,
    ],
    'translatable' => 1,
    'type' => 'text',
  ];

  // Exported field_base: 'title_field'
  $field_bases['title_field'] = [
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => [],
    'field_name' => 'title_field',
    'indexes' => [
      'format' => [
        0 => 'format',
      ],
    ],
    'locked' => 0,
    'module' => 'text',
    'settings' => [
      'entity_translation_sync' => FALSE,
      'max_length' => 255,
    ],
    'translatable' => 1,
    'type' => 'text',
  ];

  return $field_bases;
}

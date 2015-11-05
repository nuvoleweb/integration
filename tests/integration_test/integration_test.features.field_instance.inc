<?php
/**
 * @file
 * integration_test.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function integration_test_field_default_field_instances() {
  $field_instances = [];

  // Exported field_instance: 'node-integration_test-body'
  $field_instances['node-integration_test-body'] = [
    'bundle' => 'integration_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'hidden',
        'module' => 'text',
        'settings' => [],
        'type' => 'text_default',
        'weight' => 0,
      ],
      'teaser' => [
        'label' => 'hidden',
        'module' => 'text',
        'settings' => [
          'trim_length' => 600,
        ],
        'type' => 'text_summary_or_trimmed',
        'weight' => 0,
      ],
    ],
    'entity_type' => 'node',
    'field_name' => 'body',
    'label' => 'Body',
    'required' => FALSE,
    'settings' => [
      'display_summary' => TRUE,
      'entity_translation_sync' => FALSE,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'module' => 'text',
      'settings' => [
        'rows' => 20,
        'summary_rows' => 5,
      ],
      'type' => 'text_textarea_with_summary',
      'weight' => -4,
    ],
  ];

  // Exported field_instance: 'node-integration_test-field_integration_test_dates'
  $field_instances['node-integration_test-field_integration_test_dates'] = [
    'bundle' => 'integration_test',
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'date',
        'settings' => [
          'format_type' => 'long',
          'fromto' => 'both',
          'multiple_from' => '',
          'multiple_number' => '',
          'multiple_to' => '',
        ],
        'type' => 'date_default',
        'weight' => 6,
      ],
      'teaser' => [
        'label' => 'above',
        'settings' => [],
        'type' => 'hidden',
        'weight' => 0,
      ],
    ],
    'entity_type' => 'node',
    'field_name' => 'field_integration_test_dates',
    'label' => 'Dates',
    'required' => 0,
    'settings' => [
      'default_value' => 'blank',
      'default_value2' => 'same',
      'default_value_code' => '',
      'default_value_code2' => '',
      'entity_translation_sync' => FALSE,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'date',
      'settings' => [
        'increment' => 15,
        'input_format' => 'm/d/Y - H:i:s',
        'input_format_custom' => '',
        'label_position' => 'above',
        'text_parts' => [],
        'year_range' => '-10:+10',
      ],
      'type' => 'date_select',
      'weight' => 2,
    ],
  ];

  // Exported field_instance: 'node-integration_test-field_integration_test_files'
  $field_instances['node-integration_test-field_integration_test_files'] = [
    'bundle' => 'integration_test',
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'file',
        'settings' => [],
        'type' => 'file_default',
        'weight' => 5,
      ],
      'teaser' => [
        'label' => 'above',
        'settings' => [],
        'type' => 'hidden',
        'weight' => 0,
      ],
    ],
    'entity_type' => 'node',
    'field_name' => 'field_integration_test_files',
    'label' => 'Files',
    'required' => 0,
    'settings' => [
      'description_field' => 0,
      'entity_translation_sync' => FALSE,
      'file_directory' => '',
      'file_extensions' => 'txt pdf png jpeg tiff gif jpg doc xls ods odt zip tar ppt pps odp docx xlsx pptx ppsx',
      'max_filesize' => '',
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'file',
      'settings' => [
        'progress_indicator' => 'throbber',
      ],
      'type' => 'file_generic',
      'weight' => 1,
    ],
  ];

  // Exported field_instance: 'node-integration_test-field_integration_test_images'
  $field_instances['node-integration_test-field_integration_test_images'] = [
    'bundle' => 'integration_test',
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'image',
        'settings' => [
          'image_link' => '',
          'image_style' => '',
        ],
        'type' => 'image',
        'weight' => 4,
      ],
      'teaser' => [
        'label' => 'above',
        'settings' => [],
        'type' => 'hidden',
        'weight' => 0,
      ],
    ],
    'entity_type' => 'node',
    'field_name' => 'field_integration_test_images',
    'label' => 'Images',
    'required' => 0,
    'settings' => [
      'alt_field' => 0,
      'default_image' => 0,
      'entity_translation_sync' => FALSE,
      'file_directory' => '',
      'file_extensions' => 'png gif jpg jpeg',
      'max_filesize' => '',
      'max_resolution' => '',
      'min_resolution' => '',
      'title_field' => 0,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'image',
      'settings' => [
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'throbber',
      ],
      'type' => 'image_image',
      'weight' => 0,
    ],
  ];

  // Exported field_instance: 'node-integration_test-field_integration_test_ref'
  $field_instances['node-integration_test-field_integration_test_ref'] = [
    'bundle' => 'integration_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'entityreference',
        'settings' => [
          'link' => FALSE,
        ],
        'type' => 'entityreference_label',
        'weight' => 7,
      ],
      'teaser' => [
        'label' => 'above',
        'settings' => [],
        'type' => 'hidden',
        'weight' => 0,
      ],
    ],
    'entity_type' => 'node',
    'field_name' => 'field_integration_test_ref',
    'label' => 'References',
    'required' => 0,
    'settings' => [
      'entity_translation_sync' => FALSE,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'options',
      'settings' => [],
      'type' => 'options_buttons',
      'weight' => 6,
    ],
  ];

  // Exported field_instance: 'node-integration_test-field_integration_test_terms'
  $field_instances['node-integration_test-field_integration_test_terms'] = [
    'bundle' => 'integration_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'taxonomy',
        'settings' => [],
        'type' => 'taxonomy_term_reference_link',
        'weight' => 2,
      ],
      'teaser' => [
        'label' => 'above',
        'settings' => [],
        'type' => 'hidden',
        'weight' => 0,
      ],
    ],
    'entity_type' => 'node',
    'field_name' => 'field_integration_test_terms',
    'label' => 'Terms',
    'required' => 0,
    'settings' => [
      'entity_translation_sync' => FALSE,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'options',
      'settings' => [],
      'type' => 'options_select',
      'weight' => -1,
    ],
  ];

  // Exported field_instance: 'node-integration_test-field_integration_test_text'
  $field_instances['node-integration_test-field_integration_test_text'] = [
    'bundle' => 'integration_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'text',
        'settings' => [],
        'type' => 'text_default',
        'weight' => 1,
      ],
      'teaser' => [
        'label' => 'above',
        'settings' => [],
        'type' => 'hidden',
        'weight' => 0,
      ],
    ],
    'entity_type' => 'node',
    'field_name' => 'field_integration_test_text',
    'label' => 'Text',
    'required' => 0,
    'settings' => [
      'entity_translation_sync' => FALSE,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'text',
      'settings' => [
        'rows' => 5,
      ],
      'type' => 'text_textarea',
      'weight' => -2,
    ],
  ];

  // Exported field_instance: 'node-integration_test-title_field'
  $field_instances['node-integration_test-title_field'] = [
    'bundle' => 'integration_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'settings' => [],
        'type' => 'hidden',
        'weight' => 3,
      ],
      'teaser' => [
        'label' => 'above',
        'settings' => [],
        'type' => 'hidden',
        'weight' => 0,
      ],
    ],
    'entity_type' => 'node',
    'field_name' => 'title_field',
    'label' => 'Title',
    'required' => TRUE,
    'settings' => [
      'entity_translation_sync' => FALSE,
      'hide_label' => [
        'entity' => FALSE,
        'page' => FALSE,
      ],
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'module' => 'text',
      'settings' => [
        'size' => 60,
      ],
      'type' => 'text_textfield',
      'weight' => -5,
    ],
  ];

  // Exported field_instance:
  // 'taxonomy_term-integration_test-field_integration_test_dates'
  $field_instances['taxonomy_term-integration_test-field_integration_test_dates'] = [
    'bundle' => 'integration_test',
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'date',
        'settings' => [
          'format_type' => 'long',
          'fromto' => 'both',
          'multiple_from' => '',
          'multiple_number' => '',
          'multiple_to' => '',
        ],
        'type' => 'date_default',
        'weight' => 2,
      ],
    ],
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_integration_test_dates',
    'label' => 'Dates',
    'required' => 0,
    'settings' => [
      'default_value' => 'now',
      'default_value2' => 'same',
      'default_value_code' => '',
      'default_value_code2' => '',
      'entity_translation_sync' => FALSE,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'date',
      'settings' => [
        'increment' => 15,
        'input_format' => 'm/d/Y - H:i:s',
        'input_format_custom' => '',
        'label_position' => 'above',
        'text_parts' => [],
        'year_range' => '-3:+3',
      ],
      'type' => 'date_select',
      'weight' => 5,
    ],
  ];

  // Exported field_instance:
  // 'taxonomy_term-integration_test-field_integration_test_files'
  $field_instances['taxonomy_term-integration_test-field_integration_test_files'] = [
    'bundle' => 'integration_test',
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'file',
        'settings' => [],
        'type' => 'file_default',
        'weight' => 4,
      ],
    ],
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_integration_test_files',
    'label' => 'Files',
    'required' => 0,
    'settings' => [
      'description_field' => 0,
      'entity_translation_sync' => 0,
      'file_directory' => '',
      'file_extensions' => 'txt',
      'max_filesize' => '',
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'file',
      'settings' => [
        'progress_indicator' => 'throbber',
      ],
      'type' => 'file_generic',
      'weight' => 4,
    ],
  ];

  // Exported field_instance:
  // 'taxonomy_term-integration_test-field_integration_test_images'
  $field_instances['taxonomy_term-integration_test-field_integration_test_images'] = [
    'bundle' => 'integration_test',
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'image',
        'settings' => [
          'image_link' => '',
          'image_style' => '',
        ],
        'type' => 'image',
        'weight' => 3,
      ],
    ],
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_integration_test_images',
    'label' => 'Images',
    'required' => 0,
    'settings' => [
      'alt_field' => 0,
      'default_image' => 0,
      'entity_translation_sync' => FALSE,
      'file_directory' => '',
      'file_extensions' => 'png gif jpg jpeg',
      'max_filesize' => '',
      'max_resolution' => '',
      'min_resolution' => '',
      'title_field' => 0,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'image',
      'settings' => [
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'throbber',
      ],
      'type' => 'image_image',
      'weight' => 3,
    ],
  ];

  // Exported field_instance:
  // 'taxonomy_term-integration_test-field_integration_test_text'
  $field_instances['taxonomy_term-integration_test-field_integration_test_text'] = [
    'bundle' => 'integration_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'module' => 'text',
        'settings' => [],
        'type' => 'text_default',
        'weight' => 0,
      ],
    ],
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_integration_test_text',
    'label' => 'Text',
    'required' => 0,
    'settings' => [
      'entity_translation_sync' => FALSE,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'active' => 1,
      'module' => 'text',
      'settings' => [
        'rows' => 5,
      ],
      'type' => 'text_textarea',
      'weight' => 2,
    ],
  ];

  // Exported field_instance: 'taxonomy_term-integration_test-name_field'
  $field_instances['taxonomy_term-integration_test-name_field'] = [
    'bundle' => 'integration_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => [
      'default' => [
        'label' => 'above',
        'settings' => [],
        'type' => 'hidden',
        'weight' => 1,
      ],
    ],
    'entity_type' => 'taxonomy_term',
    'field_name' => 'name_field',
    'label' => 'Name',
    'required' => TRUE,
    'settings' => [
      'entity_translation_sync' => FALSE,
      'hide_label' => [
        'entity' => FALSE,
        'page' => FALSE,
      ],
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ],
    'widget' => [
      'module' => 'text',
      'settings' => [
        'size' => 60,
      ],
      'type' => 'text_textfield',
      'weight' => 0,
    ],
  ];

  // Translatables
  // Included for use with string extractors like potx.
  t('Body');
  t('Dates');
  t('Files');
  t('Images');
  t('Name');
  t('References');
  t('Terms');
  t('Text');
  t('Title');

  return $field_instances;
}

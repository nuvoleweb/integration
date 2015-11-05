<?php

/**
 * @file
 * Node object export.
 */

$export = (object) [
  'vid' => '27',
  'uid' => '1',
  'title' => 'English title news 1',
  'log' => '',
  'status' => '1',
  'comment' => '2',
  'promote' => '0',
  'sticky' => '0',
  'nid' => '27',
  'type' => 'integration_test',
  'language' => 'en',
  'created' => '1438074280',
  'changed' => '1438074280',
  'tnid' => '0',
  'translate' => '0',
  'revision_timestamp' => '1438074280',
  'revision_uid' => '1',
  'body' => [
    'en' => [
      [
        'value' => 'Processed English abstract news 1',
        'summary' => NULL,
        'format' => 'plain_text',
        'safe_value' => "<p>Processed English abstract news 1</p>\n",
        'safe_summary' => '',
      ],
    ],
    'fr' => [
      [
        'value' => 'Processed French abstract news 1',
        'summary' => NULL,
        'format' => 'plain_text',
        'safe_value' => "<p>Processed French abstract news 1</p>\n",
        'safe_summary' => '',
      ],
    ],
  ],
  'field_integration_test_dates' => [],
  'field_integration_test_files' => [],
  'field_integration_test_images' => [],
  'field_integration_test_ref' => [],
  'field_integration_test_terms' => [],
  'field_integration_test_text' => [],
  'title_field' => [
    'en' => [
      [
        'value' => 'English title news 1',
        'format' => NULL,
        'safe_value' => 'English title news 1',
      ],
    ],
    'fr' => [
      [
        'value' => 'French title news 1',
        'format' => NULL,
        'safe_value' => 'French title news 1',
      ],
    ],
  ],
  'translations' => (object) [
    'original' => 'en',
    'data' => [
      'en' => [
        'entity_type' => 'node',
        'entity_id' => '27',
        'revision_id' => '27',
        'language' => 'en',
        'source' => '',
        'uid' => '1',
        'status' => '1',
        'translate' => '0',
        'created' => '1438074280',
        'changed' => '1438074280',
      ],
      'fr' => [
        'entity_type' => 'node',
        'entity_id' => '27',
        'revision_id' => '27',
        'language' => 'fr',
        'source' => 'en',
        'uid' => '1',
        'status' => '1',
        'translate' => '0',
        'created' => '1438074280',
        'changed' => '1438074280',
      ],
    ],
  ],
  'title_original' => 'English title news 1',
  'entity_translation_handler_id' => 'node-eid-27-27',
  'rdf_mapping' => [
    'rdftype' => [
      'sioc:Item',
      'foaf:Document',
    ],
    'title' => [
      'predicates' => [
        'dc:title',
      ],
    ],
    'created' => [
      'predicates' => [
        'dc:date',
        'dc:created',
      ],
      'datatype' => 'xsd:dateTime',
      'callback' => 'date_iso8601',
    ],
    'changed' => [
      'predicates' => [
        'dc:modified',
      ],
      'datatype' => 'xsd:dateTime',
      'callback' => 'date_iso8601',
    ],
    'body' => [
      'predicates' => [
        'content:encoded',
      ],
    ],
    'uid' => [
      'predicates' => [
        'sioc:has_creator',
      ],
      'type' => 'rel',
    ],
    'name' => [
      'predicates' => [
        'foaf:name',
      ],
    ],
    'comment_count' => [
      'predicates' => [
        'sioc:num_replies',
      ],
      'datatype' => 'xsd:integer',
    ],
    'last_activity' => [
      'predicates' => [
        'sioc:last_activity_date',
      ],
      'datatype' => 'xsd:dateTime',
      'callback' => 'date_iso8601',
    ],
  ],
  'cid' => '0',
  'last_comment_timestamp' => '1438074280',
  'last_comment_name' => NULL,
  'last_comment_uid' => '1',
  'comment_count' => '0',
  'name' => 'admin',
  'picture' => '0',
  'data' => 'b:0;',
  'path' => FALSE,
  'menu' => NULL,
  'node_export_drupal_version' => '7',
];

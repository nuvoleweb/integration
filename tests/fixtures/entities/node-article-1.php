<?php

/**
 * @file
 * Node object export.
 */

$export = (object) [
  'vid' => '1',
  'uid' => '1',
  'title' => 'Title',
  'log' => '',
  'status' => '1',
  'comment' => '2',
  'promote' => '1',
  'sticky' => '0',
  'nid' => '1',
  'type' => 'article',
  'language' => 'en',
  'created' => '1455022594',
  'changed' => '1455022594',
  'tnid' => '0',
  'translate' => '0',
  'revision_timestamp' => '1455022594',
  'revision_uid' => '1',
  'body' => [
    'en' => [
      [
        'value' => 'Body',
        'summary' => '',
        'format' => 'filtered_html',
        'safe_value' => "<p>Body</p>\n",
        'safe_summary' => '',
      ],
    ],
  ],
  'field_tags' => [],
  'field_image' => [
    'und' => [
      [
        'fid' => '17',
        'uid' => '1',
        'filename' => 'image.jpg',
        'uri' => 'public://field/image/image.jpg',
        'filemime' => 'image/jpeg',
        'filesize' => '187125',
        'status' => '1',
        'timestamp' => '1455022594',
        'rdf_mapping' => [],
        'alt' => '',
        'title' => '',
        'width' => '1200',
        'height' => '999',
      ],
    ],
  ],
  'rdf_mapping' => [
    'field_image' => [
      'predicates' => [
        'og:image',
        'rdfs:seeAlso',
      ],
      'type' => 'rel',
    ],
    'field_tags' => [
      'predicates' => [
        'dc:subject',
      ],
      'type' => 'rel',
    ],
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
  'path' => [
    'pid' => '1',
    'source' => 'node/13',
    'alias' => 'content/title',
    'language' => 'en',
  ],
  'translations' => (object) [
    'original' => NULL,
    'data' => [],
  ],
  'cid' => '0',
  'last_comment_timestamp' => '1455022594',
  'last_comment_name' => NULL,
  'last_comment_uid' => '1',
  'comment_count' => '0',
  'name' => 'admin',
  'picture' => '0',
  'data' => 'b:0;',
  'menu' => NULL,
];

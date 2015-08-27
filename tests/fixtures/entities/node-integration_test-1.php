<?php

/**
 * @file
 * Node object export.
 */

$export = (object) array(
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
  'body' => array(
    'en' => array(
      array(
        'value' => 'Processed English abstract news 1',
        'summary' => NULL,
        'format' => 'plain_text',
        'safe_value' => "<p>Processed English abstract news 1</p>\n",
        'safe_summary' => '',
      ),
    ),
    'fr' => array(
      array(
        'value' => 'Processed French abstract news 1',
        'summary' => NULL,
        'format' => 'plain_text',
        'safe_value' => "<p>Processed French abstract news 1</p>\n",
        'safe_summary' => '',
      ),
    ),
  ),
  'field_integration_test_dates' => array(),
  'field_integration_test_files' => array(),
  'field_integration_test_images' => array(),
  'field_integration_test_ref' => array(),
  'field_integration_test_terms' => array(),
  'field_integration_test_text' => array(),
  'title_field' => array(
    'en' => array(
      array(
        'value' => 'English title news 1',
        'format' => NULL,
        'safe_value' => 'English title news 1',
      ),
    ),
    'fr' => array(
      array(
        'value' => 'French title news 1',
        'format' => NULL,
        'safe_value' => 'French title news 1',
      ),
    ),
  ),
  'translations' => (object) array(
    'original' => 'en',
    'data' => array(
      'en' => array(
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
      ),
      'fr' => array(
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
      ),
    ),
  ),
  'title_original' => 'English title news 1',
  'entity_translation_handler_id' => 'node-eid-27-27',
  'rdf_mapping' => array(
    'rdftype' => array(
      'sioc:Item',
      'foaf:Document',
    ),
    'title' => array(
      'predicates' => array(
        'dc:title',
      ),
    ),
    'created' => array(
      'predicates' => array(
        'dc:date',
        'dc:created',
      ),
      'datatype' => 'xsd:dateTime',
      'callback' => 'date_iso8601',
    ),
    'changed' => array(
      'predicates' => array(
        'dc:modified',
      ),
      'datatype' => 'xsd:dateTime',
      'callback' => 'date_iso8601',
    ),
    'body' => array(
      'predicates' => array(
        'content:encoded',
      ),
    ),
    'uid' => array(
      'predicates' => array(
        'sioc:has_creator',
      ),
      'type' => 'rel',
    ),
    'name' => array(
      'predicates' => array(
        'foaf:name',
      ),
    ),
    'comment_count' => array(
      'predicates' => array(
        'sioc:num_replies',
      ),
      'datatype' => 'xsd:integer',
    ),
    'last_activity' => array(
      'predicates' => array(
        'sioc:last_activity_date',
      ),
      'datatype' => 'xsd:dateTime',
      'callback' => 'date_iso8601',
    ),
  ),
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
);

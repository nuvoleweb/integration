<?php
/**
 * @file
 * integration_test.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function integration_test_taxonomy_default_vocabularies() {
  return [
    'integration_test' => [
      'name' => 'Integration test',
      'machine_name' => 'integration_test',
      'description' => 'Integration test vocabulary.',
      'hierarchy' => 0,
      'module' => 'taxonomy',
      'weight' => 0,
      'rdf_mapping' => [
        'rdftype' => [
          0 => 'skos:ConceptScheme',
        ],
        'name' => [
          'predicates' => [
            0 => 'dc:title',
          ],
        ],
        'description' => [
          'predicates' => [
            0 => 'rdfs:comment',
          ],
        ],
      ],
    ],
  ];
}

<?php
/**
 * @file
 * Contains JsonFormatterTest.
 */

namespace Drupal\integration\Tests\Backend;

use Drupal\integration\Backend\Formatter\JsonFormatter;
use Drupal\integration\Document\Document;

/**
 * Class JsonFormatterTest.
 *
 * @group backend
 * @group formatter
 *
 * @package Drupal\integration\Tests\Backend
 */
class JsonFormatterTest extends \PHPUnit_Framework_TestCase {

  /**
   * Test Json formatter.
   */
  public function testFormatter() {

    $document = new Document();
    $formatter = new JsonFormatter();
    $expected = <<<EOD
{
    "_id": null,
    "default_language": "en",
    "languages": [
        "en"
    ],
    "fields": {}
}
EOD;
    $this->assertEquals($expected, $formatter->encode($document));
    $this->assertEquals('json', $formatter->getExtension());
    $this->assertEquals('application/json', $formatter->getContentType());
  }

}

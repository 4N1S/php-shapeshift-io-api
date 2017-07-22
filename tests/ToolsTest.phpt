<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\ShapeShiftIo\Tests;

require_once __DIR__ . '/bootstrap.php';

use Achse\ShapeShiftIo\Tools;
use Nette\SmartObject;
use Tester\Assert;
use Tester\TestCase;

class ToolsTest extends TestCase
{

    use SmartObject;

    /**
     * @dataProvider getDataForJsonFloatsToString
     *
     * @param string $expected
     * @param string $inputJson
     */
    public function testJsonFloatsToString(string $expected, string $inputJson)
    {
        Assert::equal($expected, Tools::jsonNumbersToString($inputJson));
    }

    /**
     * @return array
     */
    public function getDataForJsonFloatsToString()
    {
        return [
            ['', ''],
            ['{}', '{}'],
            ['{"a": "A"}', '{"a": "A"}'],
            ['{"a": "1"}', '{"a": "1"}'],
            ['{"a": "1"}', '{"a": 1}'],

            ['{"a": "1.111"}', '{"a": "1.111"}'],
            ['{"a": "1.111"}', '{"a": 1.111}'],

            ['{"a": "1.111", "b": [{"c": "1.1234567890"}]}', '{"a": 1.111, "b": [{"c": 1.1234567890}]}'],

            [
                '{"first_name":"sample", "last_name": "lastname", "integer": "100", "float": "1555.20", "createddate": "2015-06-25 09:57:28"}',
                '{"first_name":"sample", "last_name": "lastname", "integer": 100, "float": 1555.20, "createddate": "2015-06-25 09:57:28"}',
            ]
        ];
    }

}

(new ToolsTest())->run();

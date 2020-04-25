<?php

namespace Bitrix24ApiWrapper\Test\Library;

use Bitrix24ApiWrapper\Library;

class UtilsTest extends \PHPUnit\Framework\TestCase {

    /** @var Library\Utils */
    private $_utils;

    public function setUp(): void {
        $this->_utils = new Library\Utils();
    }

    /**
     * @param array $array
     * @param bool $expected
     * @dataProvider isAssocArrayDataProvider
     */
    public function testIsAssocArray(array $array, bool $expected): void {
        $actual = $this->_utils->isAssocArray($array);
        $this->assertSame($expected, $actual);
    }

    public function isAssocArrayDataProvider(): array {
        return [
            // array                                 expected
            [['value1', 'value2'],                   false],
            [['value1', 1 => 'value2'],              false],
            [['value1', 0 => 'value2'],              false],
            [[0 => 'value1', 1 => 'value2'],         false],
            [['value1', '255' => 'value2'],          false],
            [['0' => 'value1', 'value2'],            false],
            [[0 => 'value1', '1' => 'value2'],       false],
            [['value1', 'key' => 'value2'],          true],
            [[0 => 'value1', 'key' => 'value2'],     true],
            [[255 => 'value1', 'key' => 'value2'],   true],
            [['key' => 'value2', 255 => 'value1'],   true],
        ];
    }

    /**
     * @param string|null $json
     * @param array|null $expectedArray
     * @param string|null $expectedException
     * @dataProvider jsonDecodeDataProvider
     */
    public function testJsonDecode(?string $json, ?array $expectedArray, ?string $expectedException): void {
        if ($expectedException !== null) {
            $this->expectException($expectedException);
        }
        $actualArray = $this->_utils->jsonDecode($json);
        $this->assertEquals($expectedArray, $actualArray);
    }

    public function jsonDecodeDataProvider(): array {
        return [
            // json                    array                   exception
            [null,                     null,                   null],
            ['',                       null,                   null],
            ['[]',                     [],                     null],
            ['{}',                     [],                     null],
            ['[{}]',                   [[]],                   null],
            ['["value1", "value2"]',   ['value1', 'value2'],   null],
            ['{"key":"value"}',        ['key' => 'value'],     null],
            ['[{"key":"value"}]',      [['key' => 'value']],   null],
            ['{"j": 1 ] }',            null,                   Library\Exception\JSON::class],
            ['{',                      null,                   Library\Exception\JSON::class],
        ];
    }
}
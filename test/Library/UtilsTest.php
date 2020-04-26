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

    /**
     * @param mixed $value
     * @param bool $expected
     * @dataProvider isPositiveNumericIntDataProvider
     */
    public function testIsPositiveNumericInt($value, bool $expected): void {
        $this->assertEquals($expected, $this->_utils->isPositiveNumericInt($value));
    }

    public function isPositiveNumericIntDataProvider(): array {
        $object = new \stdClass();
        return [
            // value      expected
            [0,           false],
            [-0,          false],
            [0000000000,  false],
            [1,           true],
            [2,           true],
            [-2,          false],
            [4125421,     true],
            [4622553255,  true],
            [-4622553255, false],

            [3.01,        false],
            [-3.01,       false],
            [0423.42e0,   false],
            [-0423.42e0,  false],
            [0.0000001,   false],
            [-0.0000001,  false],

            ['',          false],
            ['fdbsfvbsd', false],
            ['//fsg',     false],
            ['[qwe]',     false],
            ['++--0',     false],
            ['5454545',   true],
            ['3',         true],
            ['-3',        false],
            ['-0',        false],
            ['0xf4c3b0',  false],
            ['-0xf4c3b0', false],
            ['0b101001',  false],
            ['-0b101001', false],
            ['0123e6',    false],
            ['-0123e6',   false],
            ['0123.45e6', false],
            ['1e0',       false],
            ['-0e0',      false],

            ['3.14',      false],
            ['-3.14',     false],
            ['0123.45e0', false],
            ['-012.45e0', false],

            ['3qwe',      false],
            ['3.14qwe',   false],
            ['qwe',       false],
            ['qwe3',      false],

            [null,        false],
            [[],          false],
            [['test'],    false],
            [[1],         false],
            [true,        false],
            [false,       false],
            [NAN,         false],
            [INF,         false],
            [-INF,        false],

            [$object,     false],
        ];
    }
}
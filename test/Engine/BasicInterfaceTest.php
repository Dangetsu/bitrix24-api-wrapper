<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use Bitrix24ApiWrapper\Engine;
use GuzzleHttp\Exception\RequestException;

class BasicInterfaceTest extends \PHPUnit\Framework\TestCase {

    public function testGet() {
        $engine = new Engine\WebHook('https://b24-xxxxxxx.bitrix24.ru/rest/1/******');
        $this->expectException(RequestException::class);
        $engine->get('test');
    }
}
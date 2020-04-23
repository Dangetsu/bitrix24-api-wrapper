<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use Bitrix24ApiWrapper\Engine;

class BasicInterfaceTest extends \PHPUnit\Framework\TestCase {

    public function testGet() {
        $engine = new Engine\WebHook('https://b24-xxxxxxx.bitrix24.ru/rest/1/******');
        $this->expectException(Engine\Exception\AccessDenied::class);
        $engine->get('test');
    }
}
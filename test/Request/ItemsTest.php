<?php

namespace Bitrix24ApiWrapper\Test\Request;

use Bitrix24ApiWrapper\Request;

class ItemsTest extends AbstractBasicTest {

    /**
     * @param Request\BasicInterface $request
     * @param array $parameters
     * @dataProvider dataProvider
     */
    public function test(Request\BasicInterface $request, array $parameters): void {
        $this->assertSame(Request\BasicInterface::METHOD_GET, $request->httpMethod());
        $this->assertSame('entity.list', $request->apiMethod());
        $this->assertSame($parameters, $request->parameters());
        $this->assertSame(Mock\Entity\SomeEntity::class, $request->responseEntity());
    }

    public function dataProvider(): array {
        return [
            // Request                                                                      parameters
            [Mock\Request\SomeItems::all(['NAME' => 'DIO'], ['ID' => 'DESC'], ['ID']),       ['select' => ['ID'], 'filter' => ['NAME' => 'DIO'], 'order' => ['ID' => 'DESC']]],
            [Mock\Request\SomeItems::all(['NAME' => 'DIO'], ['ID' => 'DESC']),               ['select' => ['*', 'UF_*', 'PHONE', 'EMAIL'], 'filter' => ['NAME' => 'DIO'], 'order' => ['ID' => 'DESC']]],
            [Mock\Request\SomeItems::all(['NAME' => 'DIO']),                                 ['select' => ['*', 'UF_*', 'PHONE', 'EMAIL'], 'filter' => ['NAME' => 'DIO']]],
            [Mock\Request\SomeItems::all(),                                                  ['select' => ['*', 'UF_*', 'PHONE', 'EMAIL']]],
            [Mock\Request\SomeItems::firstPage(),                                            ['select' => ['*', 'UF_*', 'PHONE', 'EMAIL'], 'start' => '-1']],
        ];
    }
}
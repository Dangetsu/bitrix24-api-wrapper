<?php

namespace Bitrix24ApiWrapper\Test\Request;

use Bitrix24ApiWrapper\Request;

class DeleteTest extends AbstractBasicTest {

    /**
     * @param Request\BasicInterface $request
     * @param array $parameters
     * @dataProvider dataProvider
     */
    public function test(Request\BasicInterface $request, array $parameters): void {
        $this->assertSame(Request\BasicInterface::METHOD_GET, $request->httpMethod());
        $this->assertSame('entity.delete', $request->apiMethod());
        $this->assertSame($parameters, $request->parameters());
        $this->assertNull($request->responseEntity());
    }

    public function dataProvider(): array {
        return [
            // Request                                              parameters
            [Mock\Request\SomeDelete::instance('1234'),      ['id' => '1234']]
        ];
    }
}
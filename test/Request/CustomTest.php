<?php

namespace Bitrix24ApiWrapper\Test\Request;

use Bitrix24ApiWrapper\Request;

class CustomTest extends AbstractBasicTest {

    /**
     * @param Request\BasicInterface $request
     * @param string $httpMethod
     * @param string $apiMethod
     * @param array $parameters
     * @param string|null $responseEntity
     * @dataProvider dataProvider
     */
    public function test(Request\BasicInterface $request, string $httpMethod, string $apiMethod, array $parameters, ?string $responseEntity): void {
        $this->assertSame($httpMethod, $request->httpMethod());
        $this->assertSame($apiMethod, $request->apiMethod());
        $this->assertSame($parameters, $request->parameters());
        $this->assertSame($responseEntity, $request->responseEntity());
    }

    public function dataProvider(): array {
        return [
            // Request                                                                     http method                     api method      parameters                              response entity
            [Request\Custom::get('test', ['some' => 'value', 'value2', 10]),    Request\Custom::METHOD_GET,     'test',         ['some' => 'value', 'value2', 10],      null],
            [Request\Custom::post('test', ['some' => 'value', 'value2', 10]),   Request\Custom::METHOD_POST,    'test',         ['some' => 'value', 'value2', 10],      null],
            [Request\Custom::get('test'),                                       Request\Custom::METHOD_GET,     'test',         [],                                     null],
            [Request\Custom::post('test'),                                      Request\Custom::METHOD_POST,    'test',         [],                                     null],
        ];
    }
}
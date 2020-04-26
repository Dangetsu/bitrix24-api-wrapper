<?php

namespace Bitrix24ApiWrapper\Test\Request;

use Bitrix24ApiWrapper\Request;

class SaveTest extends AbstractBasicTest {

    /**
     * @param Request\BasicInterface $request
     * @param array $parameters
     * @dataProvider dataProvider
     */
    public function test(Request\BasicInterface $request, array $parameters): void {
        $this->assertSame(Request\BasicInterface::METHOD_POST, $request->httpMethod());
        $this->assertSame('entity.save', $request->apiMethod());
        $this->assertSame($parameters, $request->parameters());
        $this->assertNull($request->responseEntity());
    }

    public function dataProvider(): array {
        $entityWithId = new Mock\Entity\SomeEntity();
        $entityWithId->ID = '6';
        $entityWithId->SOME = 'test';
        $entityWithId->UNFAMILIAR = '21314';

        $entityWithoutId = new Mock\Entity\SomeEntity();
        $entityWithoutId->SOME = 'test';
        $entityWithoutId->UNFAMILIAR = '21314';

        $entityOnlyWithId = new Mock\Entity\SomeEntity();
        $entityOnlyWithId->ID = '6';

        return [
            // Request                                                                                        parameters
            [Mock\Request\SomeSave::instance($entityWithId, ['REGISTER_SONET_EVENT' => 'Y']),                 ['fields' => ['SOME' => 'test', 'ID' => '6', 'UNFAMILIAR' => '21314'], 'id' => '6', 'params' => ['REGISTER_SONET_EVENT' => 'Y']]],
            [Mock\Request\SomeSave::instance($entityWithoutId, ['REGISTER_SONET_EVENT' => 'Y']),              ['fields' => ['SOME' => 'test', 'UNFAMILIAR' => '21314'], 'params' => ['REGISTER_SONET_EVENT' => 'Y']]],
            [Mock\Request\SomeSave::instance($entityOnlyWithId, ['REGISTER_SONET_EVENT' => 'Y']),             ['fields' => ['ID' => '6'], 'id' => '6', 'params' => ['REGISTER_SONET_EVENT' => 'Y']]],
            [Mock\Request\SomeSave::instance(new Mock\Entity\SomeEntity(), ['REGISTER_SONET_EVENT' => 'Y']),  ['fields' => [], 'params' => ['REGISTER_SONET_EVENT' => 'Y']]],

            [Mock\Request\SomeSave::instance($entityWithId),                                                  ['fields' => ['SOME' => 'test', 'ID' => '6', 'UNFAMILIAR' => '21314'], 'id' => '6']],
            [Mock\Request\SomeSave::instance($entityWithoutId),                                               ['fields' => ['SOME' => 'test', 'UNFAMILIAR' => '21314']]],
            [Mock\Request\SomeSave::instance($entityOnlyWithId),                                              ['fields' => ['ID' => '6'], 'id' => '6']],
            [Mock\Request\SomeSave::instance(new Mock\Entity\SomeEntity()),                                   ['fields' => []]],
        ];
    }
}
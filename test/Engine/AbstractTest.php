<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use Bitrix24ApiWrapper;

abstract class AbstractTest extends \PHPUnit\Framework\TestCase {

    /** @var Mock\Extension\MockedEngineInterface */
    private $_engine;

    abstract protected function _prepareUrl(string $apiMethod): string;

    abstract protected function _initEngine(): Mock\Extension\MockedEngineInterface;

    /**
     * @param string $responseFile
     * @param int $responseCode
     * @param string $exceptionClass
     * @dataProvider errorsDataProvider
     */
    public function testApiErrors(string $responseFile, int $responseCode, string $exceptionClass): void {
        $this->_engine()->setMockResponse(Entity\Mock::get('test', $responseFile, [], $responseCode));
        $this->expectException($exceptionClass);
        $this->_engine()->execute(Bitrix24ApiWrapper\Request\Custom::get('test'));
    }

    public function errorsDataProvider(): array {
        return [
            // response file                                         response code                                  expected exception class
            [__DIR__ . '/Response/error_not_found_method.json',      Entity\Mock::HTTP_CODE_NOT_FOUND,              Bitrix24ApiWrapper\Engine\Exception\MethodNotFound::class],
            [__DIR__ . '/Response/error_deleted_portal.json',        Entity\Mock::HTTP_CODE_ACCESS_DENIED,          Bitrix24ApiWrapper\Engine\Exception\PortalDeleted::class],
            [__DIR__ . '/Response/error_invalid_credentials.json',   Entity\Mock::HTTP_CODE_UNAUTHORIZED,           Bitrix24ApiWrapper\Engine\Exception\InvalidCredentials::class],
            [__DIR__ . '/Response/error_custom.json',                Entity\Mock::HTTP_CODE_INTERNAL_SERVER_ERROR,  Bitrix24ApiWrapper\Engine\Exception\Basic::class],
        ];
    }

    public function testGetClearResponseProcess(): void {
        $this->_engine()->setMockResponse(Entity\Mock::get('entity.list', __DIR__ . '/Response/entity_list.json'));
        $actualResponse = $this->_engine()->execute(Bitrix24ApiWrapper\Request\Custom::get('entity.list'));
        $this->assertEquals([
            [
                'INT' => 8,
            ],
            [
                'INT'             => 6,
                'FLOAT'           => 123.32,
                'STRING'          => 'Новый лид от JOTARO',
                'NULL'            => null,
                'BOOL'            => false,
                'ARRAY'           => ['1', '2', '3'],
                'OBJECT'          => ['ID' => '4', 'VALUE' => 'test@gmail.xcom'],
                'OBJECTS'         => [
                    ['ID' => '2', 'VALUE' => 'value1'],
                    ['ID' => '4', 'VALUE' => 'value2'],
                ],
                'UNFAMILIAR'      => '21314',
                'UNFAMILIAR_NULL' => null,
            ],
        ], $actualResponse);
    }

    public function testUnsupportedHttpMethod(): void {
        $this->expectException(Bitrix24ApiWrapper\Engine\Exception\UnsupportedHttpMethod::class);
        $this->_engine()->execute(new Bitrix24ApiWrapper\Request\Custom('PUT', 'test'));
    }

    public function testLoadItemsProcess(): void {
        $parameters = ['select' => ['*', 'UF_*', 'EMAIL', 'PHONE'], 'filter' => ['NAME' => 'DIO'], 'order' => ['ID' => 'DESC']];
        $this->_engine()->setMockResponse(Entity\Mock::get('entity.list', __DIR__ . '/Response/entity_list.json', $parameters));
        /** @var Bitrix24ApiWrapper\Entity\CRM\Lead[] $actualResponse */
        $actualResponse = $this->_engine()->execute(Mock\Request\Some::get('entity.list', $parameters));
        $this->assertEquals($this->_expectedSomeEntities(), $actualResponse);
    }

    public function testLoadItemsMultiPageProcess(): void {
        $this->_engine()->setMockResponse(Entity\Mock::get('entity.list', __DIR__ . '/Response/entity_list_page_1.json'));
        $this->_engine()->setMockResponse(Entity\Mock::get('entity.list', __DIR__ . '/Response/entity_list_page_2.json', ['start' => 1]));
        /** @var Bitrix24ApiWrapper\Entity\CRM\Lead[] $actualResponse */
        $actualResponse = $this->_engine()->execute(Mock\Request\Some::get('entity.list'));
        $this->assertEquals($this->_expectedSomeEntities(), $actualResponse);
    }

    public function testMultiPageReachedMaxCountException(): void {
        $this->_engine()->setMockResponse(Entity\Mock::get('entity.list', __DIR__ . '/Response/entity_list_page_1.json'));
        $this->_engine()->setMockResponse(Entity\Mock::get('entity.list', __DIR__ . '/Response/entity_list_page_2_with_next_page.json', ['start' => 1]));
        $this->expectException(Bitrix24ApiWrapper\Engine\Exception\ReachedMaxLoadedPageCount::class);
        /** @var Bitrix24ApiWrapper\Entity\CRM\Lead[] $actualResponse */
        $this->_engine()->execute(Mock\Request\Some::get('entity.list'));
    }

    public function testLoadItemProcess(): void {
        $parameters = ['ID' => 6];
        $this->_engine()->setMockResponse(Entity\Mock::get('entity.get', __DIR__ . '/Response/entity_get.json', $parameters));
        /** @var Bitrix24ApiWrapper\Entity\CRM\Lead[] $actualResponse */
        $actualResponse = $this->_engine()->execute(Mock\Request\Some::get('entity.get', $parameters));
        $this->assertEquals($this->_expectedSecondSomeEntity(), $actualResponse);
    }

    public function testSaveProcess(): void {
        $parameters = [
            'some_parameter' => 'some_value',
            'fields'         => [
                'TITLE'     => 'Some title',
                'NAME'      => 'Vasya',
                'STATUS_ID' => 'NEW',
            ],
        ];
        $this->_engine()->setMockResponse(Entity\Mock::post('entity.save', __DIR__ . '/Response/entity_save.json', $parameters));
        $actualResponse = $this->_engine()->execute(Mock\Request\Some::post('entity.save', $parameters));
        $this->assertSame('2', $actualResponse);
    }

    /**
     * @return Mock\Entity\SomeEntity[]
     */
    private function _expectedSomeEntities(): array {
        $firstEntity = new Mock\Entity\SomeEntity();
        $firstEntity->INT = 8;
        return [$firstEntity, $this->_expectedSecondSomeEntity()];
    }

    private function _expectedSecondSomeEntity(): Mock\Entity\SomeEntity {
        $object = new Mock\Entity\SomeObject();
        $object->ID = '4';
        $object->VALUE = 'test@gmail.xcom';

        $firstObjects = new Mock\Entity\SomeObject();
        $firstObjects->ID = '2';
        $firstObjects->VALUE = 'value1';
        $secondObjects = new Mock\Entity\SomeObject();
        $secondObjects->ID = '4';
        $secondObjects->VALUE = 'value2';

        $secondEntity = new Mock\Entity\SomeEntity();
        $secondEntity->INT = 6;
        $secondEntity->FLOAT = 123.32;
        $secondEntity->STRING = 'Новый лид от JOTARO';
        $secondEntity->NULL = null;
        $secondEntity->BOOL = false;
        $secondEntity->ARRAY = ['1', '2', '3'];
        $secondEntity->OBJECT = $object;
        $secondEntity->OBJECTS = [$firstObjects, $secondObjects];
        $secondEntity->UNFAMILIAR = '21314';
        return $secondEntity;
    }

    protected function _engine(): Mock\Extension\MockedEngineInterface {
        if ($this->_engine === null) {
            $this->_engine = $this->_initEngine();
        }
        return $this->_engine;
    }
}
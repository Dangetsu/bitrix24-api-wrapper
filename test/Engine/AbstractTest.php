<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use Bitrix24ApiWrapper\Engine;
use Bitrix24ApiWrapper\Request;
use Bitrix24ApiWrapper\Library;
use Bitrix24ApiWrapper\Entity;
use GuzzleHttp;
use GuzzleHttp\Psr7;
use Psr;

abstract class AbstractTest extends \PHPUnit\Framework\TestCase {

    protected const HTTP_CODE_SUCCESS       = 200;
    protected const HTTP_CODE_UNAUTHORIZED  = 401;
    protected const HTTP_CODE_ACCESS_DENIED = 403;
    protected const HTTP_CODE_NOT_FOUND     = 404;

    private const METHOD_GET  = 'GET';

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
    public function testErrors(string $responseFile, int $responseCode, string $exceptionClass): void {
        $this->_engine()->setMockResponse(
            $this->_prepareMockGetRequest('test'),
            $this->_prepareMockResponse($responseFile, $responseCode)
        );
        $this->expectException($exceptionClass);
        $this->_engine()->execute(Request\Custom::get('test'));
    }

    public function errorsDataProvider(): array {
        return [
            // response file                                         response code                  expected exception class
            [__DIR__ . '/Response/error_not_found_method.json',      self::HTTP_CODE_NOT_FOUND,     Engine\Exception\MethodNotFound::class],
            [__DIR__ . '/Response/error_deleted_portal.json',        self::HTTP_CODE_ACCESS_DENIED, Engine\Exception\PortalDeleted::class],
            [__DIR__ . '/Response/error_invalid_credentials.json',   self::HTTP_CODE_UNAUTHORIZED,  Engine\Exception\InvalidCredentials::class],
        ];
    }

    public function testSendGetRequest(): void {
        $this->_engine()->setMockResponse(
            $this->_prepareMockGetRequest('crm.lead.list'),
            $this->_prepareMockResponse(__DIR__ . '/Response/entity_list.json')
        );
        $actualResponse = $this->_engine()->execute(Request\Custom::get('crm.lead.list'));
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

    public function testSendItemsRequest(): void {
        $parameters = ['select' => ['*', 'UF_*', 'EMAIL', 'PHONE'], 'filter' => ['NAME' => 'DIO'], 'order' => ['ID' => 'DESC']];
        $this->_engine()->setMockResponse(
            $this->_prepareMockGetRequest('entity.list', $parameters),
            $this->_prepareMockResponse(__DIR__ . '/Response/entity_list.json')
        );
        /** @var Entity\CRM\Lead[] $actualResponse */
        $actualResponse = $this->_engine()->execute(Mock\Request\Some::get('entity.list', $parameters));
        $this->assertEquals($this->_expectedSomeEntities(), $actualResponse);
    }

    /**
     * @return Mock\Entity\SomeEntity[]
     */
    private function _expectedSomeEntities(): array {
        $firstEntity = new Mock\Entity\SomeEntity();
        $firstEntity->INT = 8;

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
        return [$firstEntity, $secondEntity];
    }

    protected function _engine(): Mock\Extension\MockedEngineInterface {
        if ($this->_engine === null) {
            $this->_engine = $this->_initEngine();
        }
        return $this->_engine;
    }

    protected function _prepareMockGetRequest(string $apiMethod, array $parameters = []): Psr\Http\Message\RequestInterface {
        $url = $this->_prepareUrl($apiMethod);
        $delimiter = mb_strpos($url, '?') !== false ? '&' : '?';
        $urlWithQuery = "{$url}{$delimiter}" . http_build_query($parameters);
        return new Psr7\Request(self::METHOD_GET, $urlWithQuery, [], null);
    }

    protected function _prepareMockResponse(string $responseFile, int $statusCode = self::HTTP_CODE_SUCCESS): Psr\Http\Message\ResponseInterface {
        return new GuzzleHttp\Psr7\Response($statusCode, ['Content-Type' => 'application/json'], file_get_contents($responseFile));
    }
}
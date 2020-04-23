<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use Bitrix24ApiWrapper\Engine\Exception;

class WebHookTest extends AbstractTest {

    private const WEB_HOOK = 'https://b24-xxxxxxx.bitrix24.ru/rest/1/******';

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
        $this->_engine()->get('test');
    }

    public function errorsDataProvider(): array {
        return [
            // response file                                         response code                  expected exception class
            [__DIR__ . '/Response/error_not_found_method.json',      self::HTTP_CODE_NOT_FOUND,     Exception\MethodNotFound::class],
            [__DIR__ . '/Response/error_deleted_portal.json',        self::HTTP_CODE_ACCESS_DENIED, Exception\PortalDeleted::class],
            [__DIR__ . '/Response/error_invalid_credentials.json',   self::HTTP_CODE_UNAUTHORIZED,  Exception\InvalidCredentials::class],
        ];
    }

    protected function _prepareUrl(string $apiMethod): string {
        return self::WEB_HOOK . "/{$apiMethod}";
    }

    protected function _initEngine(): Mock\Extension\MockedEngineInterface {
        return new Mock\WebHook(self::WEB_HOOK);
    }
}
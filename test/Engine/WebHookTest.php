<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use Bitrix24ApiWrapper\Engine\Exception;

class WebHookTest extends AbstractTest {

    private const WEB_HOOK = 'https://b24-xxxxxxx.bitrix24.ru/rest/1/******';

    public function testDeletedPortal(): void {
        $this->_engine()->setMockResponse(
            $this->_prepareMockGetRequest('test'),
            $this->_prepareMockResponse(__DIR__ . '/Response/error_deleted_portal.json', self::HTTP_CODE_ACCESS_DENIED)
        );
        $this->expectException(Exception\AccessDenied::class);
        $this->_engine()->get('test');
    }

    public function testNotFound(): void {
        $this->_engine()->setMockResponse(
            $this->_prepareMockGetRequest('test'),
            $this->_prepareMockResponse(__DIR__ . '/Response/error_deleted_portal.json', self::HTTP_CODE_NOT_FOUND));
        $this->expectException(Exception\NotFound::class);
        $this->_engine()->get('test');
    }

    protected function _prepareUrl(string $apiMethod): string {
        return self::WEB_HOOK . "/{$apiMethod}";
    }

    protected function _initEngine(): Mock\Extension\MockedEngineInterface {
        return new Mock\WebHook(self::WEB_HOOK);
    }
}
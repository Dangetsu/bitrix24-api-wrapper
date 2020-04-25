<?php

namespace Bitrix24ApiWrapper\Test\Engine;

class WebHookTest extends AbstractTest {

    private const WEB_HOOK = 'https://b24-xxxxxxx.bitrix24.ru/rest/1/******';

    protected function _prepareUrl(string $apiMethod): string {
        return self::WEB_HOOK . "/{$apiMethod}";
    }

    protected function _initEngine(): Mock\Extension\MockedEngineInterface {
        return new Mock\WebHook(self::WEB_HOOK);
    }
}
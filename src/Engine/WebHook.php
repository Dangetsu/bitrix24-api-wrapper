<?php

namespace Bitrix24ApiWrapper\Engine;

class WebHook extends AbstractBasic {

    /** @var string */
    private $_webHook;

    public function __construct(string $webHook) {
        $this->_webHook = $webHook;
    }

    protected function _prepareUrl(string $apiMethod): string {
        return "{$this->_webHook}/{$apiMethod}";
    }
}
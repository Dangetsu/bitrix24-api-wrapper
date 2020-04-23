<?php

namespace Bitrix24ApiWrapper\Test\Engine\Mock\Extension;

use Bitrix24ApiWrapper\Engine;
use Psr;

interface MockedEngineInterface extends Engine\BasicInterface {

    public function setMockResponse(Psr\Http\Message\RequestInterface $request, Psr\Http\Message\ResponseInterface $response): void;
}
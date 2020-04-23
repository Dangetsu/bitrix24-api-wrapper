<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use GuzzleHttp;
use GuzzleHttp\Psr7;
use Psr;

abstract class AbstractTest extends \PHPUnit\Framework\TestCase {

    protected const HTTP_CODE_UNAUTHORIZED  = 401;
    protected const HTTP_CODE_ACCESS_DENIED = 403;
    protected const HTTP_CODE_NOT_FOUND     = 404;

    private const METHOD_GET  = 'GET';

    /** @var Mock\Extension\MockedEngineInterface */
    private $_engine;

    abstract protected function _prepareUrl(string $apiMethod): string;

    abstract protected function _initEngine(): Mock\Extension\MockedEngineInterface;

    protected function _engine(): Mock\Extension\MockedEngineInterface {
        if ($this->_engine === null) {
            $this->_engine = $this->_initEngine();
        }
        return $this->_engine;
    }

    protected function _prepareMockGetRequest(string $apiMethod): Psr\Http\Message\RequestInterface {
        return new Psr7\Request(self::METHOD_GET, $this->_prepareUrl($apiMethod), [], null);
    }

    protected function _prepareMockResponse(string $responseFile, int $statusCode = 200): Psr\Http\Message\ResponseInterface {
        return new GuzzleHttp\Psr7\Response($statusCode, ['Content-Type' => 'application/json'], file_get_contents($responseFile));
    }
}
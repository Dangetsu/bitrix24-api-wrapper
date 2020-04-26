<?php

namespace Bitrix24ApiWrapper\Test\Engine\Mock\Extension;

use Bitrix24ApiWrapper\Test\Engine\Exception;
use GuzzleHttp;
use Psr;

trait MockedEngineTrait {

    /** @var array */
    private $_mockedResponseStack = [];

    public function setMockResponse(Psr\Http\Message\RequestInterface $request, Psr\Http\Message\ResponseInterface $response): void {
        $key = $this->_calcMd5ByRequest($request);
        $this->_mockedResponseStack[$key] = $response;
    }

    protected function _httpClient(): GuzzleHttp\ClientInterface {
        $stack = GuzzleHttp\HandlerStack::create(new GuzzleHttp\Handler\MockHandler());
        $stack->push(function (callable $handler): callable {
            return $this->_responseMockingProcessor($handler);
        });
        return new GuzzleHttp\Client(['handler' => $stack]);
    }

    protected function _maxLoadedPageCount(): int {
        return 1;
    }

    private function _responseMockingProcessor(GuzzleHttp\Handler\MockHandler $handler): callable {
        return function (Psr\Http\Message\RequestInterface $request, array $options) use ($handler): GuzzleHttp\Promise\PromiseInterface {
            $requestMd5 = $this->_calcMd5ByRequest($request);
            $response = $this->_mockedResponseStack[$requestMd5] ?? null;
            if ($response === null) {
                $actualRequestData = $this->_serializeRequest($request);
                throw new Exception\MockedResponseNotFound("Mocked response not found. Request: {$actualRequestData}");
            }
            $handler->append($response);
            return $handler($request, $options);
        };
    }

    private function _calcMd5ByRequest(Psr\Http\Message\RequestInterface $request): string {
        return md5($this->_serializeRequest($request));
    }

    // todo: find some better logic for compare requests
    private function _serializeRequest(Psr\Http\Message\RequestInterface $request): string {
        $uri = $request->getUri();
        return json_encode([
            'method' => $request->getMethod(),
            'uri'    => "{$uri->getScheme()}://{$uri->getHost()}:{$uri->getPort()}/{$uri->getPath()}?{$uri->getQuery()}#{$uri->getFragment()}",
            'body'   => $request->getBody()->getContents(),
        ]);
    }
}
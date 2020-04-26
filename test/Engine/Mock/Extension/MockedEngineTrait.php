<?php

namespace Bitrix24ApiWrapper\Test\Engine\Mock\Extension;

use Bitrix24ApiWrapper\Test;
use GuzzleHttp;
use Psr;

trait MockedEngineTrait {

    /** @var array */
    private $_mockedResponseStack = [];

    public function setMockResponse(Test\Engine\Entity\Mock $mock): void {
        $request = $mock->isGetHttpMethod()
            ? $this->_prepareMockGetRequest($mock->apiMethod(), $mock->parameters())
            : $this->_prepareMockPostRequest($mock->apiMethod(), $mock->parameters());
        $response = $this->_prepareMockResponse($mock->responseFile(), $mock->responseCode());
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
                throw new Test\Engine\Exception\MockedResponseNotFound("Mocked response not found. Request: {$actualRequestData}");
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
        $data = [
            'method' => $request->getMethod(),
            'uri'    => "{$uri->getScheme()}://{$uri->getHost()}:{$uri->getPort()}/{$uri->getPath()}?{$uri->getQuery()}#{$uri->getFragment()}",
            'body'   => $request->getBody()->getContents(),
        ];
        return json_encode($data);
    }

    private function _prepareMockGetRequest(string $apiMethod, array $parameters = []): Psr\Http\Message\RequestInterface {
        $url = $this->_prepareUrl($apiMethod);
        $delimiter = mb_strpos($url, '?') !== false ? '&' : '?';
        $urlWithQuery = "{$url}{$delimiter}" . http_build_query($parameters);
        return new GuzzleHttp\Psr7\Request(Test\Engine\Entity\Mock::METHOD_GET, $urlWithQuery);
    }

    private function _prepareMockPostRequest(string $apiMethod, array $parameters = []): Psr\Http\Message\RequestInterface {
        return new GuzzleHttp\Psr7\Request(Test\Engine\Entity\Mock::METHOD_POST, $this->_prepareUrl($apiMethod), [], http_build_query($parameters));
    }

    private function _prepareMockResponse(string $responseFile, int $statusCode = Test\Engine\Entity\Mock::HTTP_CODE_SUCCESS): Psr\Http\Message\ResponseInterface {
        return new GuzzleHttp\Psr7\Response($statusCode, ['Content-Type' => 'application/json'], file_get_contents($responseFile));
    }
}
<?php

namespace Bitrix24ApiWrapper\Engine;

use GuzzleHttp;

abstract class AbstractBasic implements BasicInterface {

    private const METHOD_GET  = 'GET';
    private const METHOD_POST = 'POST';

    private const HTTP_CODE_ACCESS_DENIED = 403;
    private const HTTP_CODE_NOT_FOUND     = 404;

    abstract protected function _prepareUrl(string $apiMethod): string;

    /**
     * @param string $apiMethod
     * @param array $parameters
     * @return mixed
     */
    public function get(string $apiMethod, array $parameters = []) {
        return $this->_request(self::METHOD_GET, $apiMethod, $parameters);
    }

    /**
     * @param string $apiMethod
     * @param array $parameters
     * @return mixed
     */
    public function post(string $apiMethod, array $parameters = []) {
        return $this->_request(self::METHOD_POST, $apiMethod, $parameters);
    }

    protected function _httpClient(): GuzzleHttp\ClientInterface {
        return new GuzzleHttp\Client();
    }

    /**
     * @param string $httpMethod
     * @param string $apiMethod
     * @param array $parameters
     * @return mixed
     */
    private function _request(string $httpMethod, string $apiMethod, array $parameters = []) {
        try {
            $res = $this->_httpClient()->request($httpMethod, $this->_prepareUrl($apiMethod), $parameters);
            return $res->getBody()->getContents();
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            throw $this->_replaceRequestException($exception);
        }
    }

    private function _replaceRequestException(GuzzleHttp\Exception\RequestException $exception): Exception\Basic {
        switch ($exception->getCode()) {
            case self::HTTP_CODE_ACCESS_DENIED:
                return new Exception\AccessDenied($exception->getMessage());
            case self::HTTP_CODE_NOT_FOUND:
                return new Exception\NotFound($exception->getMessage());
            default:
                return new Exception\Basic($exception->getMessage());
        }
    }
}
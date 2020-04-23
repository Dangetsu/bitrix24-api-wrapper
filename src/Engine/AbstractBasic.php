<?php

namespace Bitrix24ApiWrapper\Engine;

use GuzzleHttp;

abstract class AbstractBasic {

    private const METHOD_GET  = 'GET';
    private const METHOD_POST = 'POST';

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
        $res = $this->_httpClient()->request($httpMethod, $this->_prepareUrl($apiMethod), $parameters);
        return $res->getBody();
    }
}
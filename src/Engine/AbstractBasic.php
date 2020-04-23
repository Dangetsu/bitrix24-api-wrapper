<?php

namespace Bitrix24ApiWrapper\Engine;

use GuzzleHttp;

abstract class AbstractBasic implements BasicInterface {

    private const METHOD_GET  = 'GET';
    private const METHOD_POST = 'POST';

    private const ERROR_PORTAL_DELETED      = 'PORTAL_DELETED';
    private const ERROR_METHOD_NOT_FOUND    = 'ERROR_METHOD_NOT_FOUND';
    private const ERROR_INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';

    private const RESPONSE_RESULT = 'result';

    abstract protected function _prepareUrl(string $apiMethod): string;

    /**
     * @param string $apiMethod
     * @param array $parameters
     * @return mixed
     */
    public function get(string $apiMethod, array $parameters = []) {
        return $this->_request(self::METHOD_GET, $apiMethod, $parameters)[self::RESPONSE_RESULT];
    }

    /**
     * @param string $apiMethod
     * @param array $parameters
     * @return mixed
     */
    public function post(string $apiMethod, array $parameters = []) {
        return $this->_request(self::METHOD_POST, $apiMethod, $parameters)[self::RESPONSE_RESULT];
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
            $data = $res->getBody()->getContents();
            return json_decode($data, true);
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            throw $this->_replaceRequestException($exception);
        }
    }

    private function _replaceRequestException(GuzzleHttp\Exception\RequestException $exception): Exception\Basic {
        $body = $exception->getResponse()->getBody()->getContents();
        $decodedBody = json_decode($body, true);
        $error = $decodedBody['error'] ?? null;
        switch ($error) {
            case self::ERROR_PORTAL_DELETED:
                return new Exception\PortalDeleted($exception->getMessage());
            case self::ERROR_METHOD_NOT_FOUND:
                return new Exception\MethodNotFound($exception->getMessage());
            case self::ERROR_INVALID_CREDENTIALS:
                return new Exception\InvalidCredentials($exception->getMessage());
            default:
                return new Exception\Basic($exception->getMessage());
        }
    }
}
<?php

namespace Bitrix24ApiWrapper\Engine;

use Bitrix24ApiWrapper\Request;
use Bitrix24ApiWrapper\Library;
use GuzzleHttp;

abstract class AbstractBasic implements BasicInterface {

    private const ERROR_PORTAL_DELETED      = 'PORTAL_DELETED';
    private const ERROR_METHOD_NOT_FOUND    = 'ERROR_METHOD_NOT_FOUND';
    private const ERROR_INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';

    private const RESPONSE_RESULT = 'result';

    /** @var Builder\BasicInterface */
    private $_builder;

    /** @var GuzzleHttp\ClientInterface */
    private $_httpClient;

    /** @var Library\Utils */
    private $_utils;

    abstract protected function _prepareUrl(string $apiMethod): string;

    /**
     * @param Request\BasicInterface $request
     * @return mixed
     */
    public function execute(Request\BasicInterface $request) {
        $response = $this->_request($request->httpMethod(), $request->apiMethod(), $request->parameters());
        $result = $response[self::RESPONSE_RESULT] ?? null;
        return $this->_prepareResponse($request->responseEntity(), $result);
    }

    protected function _builder(): Builder\BasicInterface {
        if ($this->_builder === null) {
            $this->_builder = new Builder\Basic();
        }
        return $this->_builder;
    }

    protected function _httpClient(): GuzzleHttp\ClientInterface {
        if ($this->_httpClient === null) {
            $this->_httpClient = new GuzzleHttp\Client();
        }
        return $this->_httpClient;
    }

    protected function _utils(): Library\Utils {
        if ($this->_utils === null) {
            $this->_utils = new Library\Utils();
        }
        return $this->_utils;
    }

    /**
     * @param string|null $responseEntity
     * @param mixed $data
     * @return mixed
     */
    private function _prepareResponse(?string $responseEntity, $data) {
        if ($responseEntity === null || !is_array($data)) {
            return $data;
        }
        if ($this->_utils()->isAssocArray($data)) {
            return $this->_builder()->buildEntity($responseEntity, $data);
        }
        return array_map(function (array $entityData) use($responseEntity) {
            return $this->_builder()->buildEntity($responseEntity, $entityData);
        }, $data);
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
            return $this->_utils()->jsonDecode($data);
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            throw $this->_replaceRequestException($exception);
        }
    }

    private function _replaceRequestException(GuzzleHttp\Exception\RequestException $exception): Exception\Basic {
        $body = $exception->getResponse() !== null ? $exception->getResponse()->getBody()->getContents() : null;
        $decodedBody = $this->_utils()->jsonDecode($body);
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
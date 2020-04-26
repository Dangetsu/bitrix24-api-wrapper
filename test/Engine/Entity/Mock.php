<?php

namespace Bitrix24ApiWrapper\Test\Engine\Entity;

class Mock {

    public const METHOD_GET  = 'GET';
    public const METHOD_POST = 'POST';

    public const HTTP_CODE_SUCCESS       = 200;
    public const HTTP_CODE_UNAUTHORIZED  = 401;
    public const HTTP_CODE_ACCESS_DENIED = 403;
    public const HTTP_CODE_NOT_FOUND     = 404;

    /** @var string */
    private $_httpMethod;

    /** @var string */
    private $_apiMethod;

    /** @var array */
    private $_parameters;

    /** @var string */
    private $_responseFile;

    /** @var int */
    private $_responseCode;

    public static function get(string $apiMethod, string $responseFile, array $parameters = [], int $responseCode = self::HTTP_CODE_SUCCESS): self {
        return new self(self::METHOD_GET, $apiMethod, $responseFile, $parameters, $responseCode);
    }

    public static function post(string $apiMethod, string $responseFile, array $parameters = [], int $responseCode = self::HTTP_CODE_SUCCESS): self {
        return new self(self::METHOD_POST, $apiMethod, $responseFile, $parameters, $responseCode);
    }

    public function __construct(string $httpMethod, string $apiMethod, string $responseFile, array $parameters = [], int $responseCode = self::HTTP_CODE_SUCCESS) {
        $this->_httpMethod = $httpMethod;
        $this->_apiMethod = $apiMethod;
        $this->_parameters = $parameters;
        $this->_responseFile = $responseFile;
        $this->_responseCode = $responseCode;
    }

    public function httpMethod(): string {
        return $this->_httpMethod;
    }

    public function isGetHttpMethod(): bool {
        return $this->_httpMethod === self::METHOD_GET;
    }

    public function apiMethod(): string {
        return $this->_apiMethod;
    }

    public function parameters(): array {
        return $this->_parameters;
    }

    public function responseFile(): string {
        return $this->_responseFile;
    }

    public function responseCode(): int {
        return $this->_responseCode;
    }
}
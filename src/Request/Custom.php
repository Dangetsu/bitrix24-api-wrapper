<?php

namespace Bitrix24ApiWrapper\Request;

class Custom implements BasicInterface {

    /** @var string */
    private $_httpMethod;

    /** @var string */
    private $_apiMethod;

    /** @var array */
    private $_parameters;

    public static function get(string $apiMethod, array $parameters = []): self {
        return new self(self::METHOD_GET, $apiMethod, $parameters);
    }

    public static function post(string $apiMethod, array $parameters = []): self {
        return new self(self::METHOD_POST, $apiMethod, $parameters);
    }

    public function __construct(string $httpMethod, string $apiMethod, array $parameters = []) {
        $this->_httpMethod = $httpMethod;
        $this->_apiMethod  = $apiMethod;
        $this->_parameters = $parameters;
    }

    public function httpMethod(): string {
        return $this->_httpMethod;
    }

    public function apiMethod(): string {
        return $this->_apiMethod;
    }

    public function parameters(): array {
        return $this->_parameters;
    }

    public function responseEntity(): ?string {
        return null;
    }
}
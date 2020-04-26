<?php

namespace Bitrix24ApiWrapper\Request;

abstract class AbstractDelete implements BasicInterface {

    /** @var string */
    private $_entityId;

    /**
     * @param string $entityId
     * @return static
     */
    public static function instance(string $entityId): self {
        return new static($entityId);
    }

    public function __construct(string $entityId) {
        $this->_entityId = $entityId;
    }

    public function httpMethod(): string {
        return self::METHOD_GET;
    }

    public function parameters(): array {
        return ['id' => $this->_entityId];
    }

    public function responseEntity(): ?string {
        return null;
    }
}
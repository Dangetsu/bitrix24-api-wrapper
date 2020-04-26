<?php

namespace Bitrix24ApiWrapper\Request;

use Bitrix24ApiWrapper\Entity;

abstract class AbstractSave implements BasicInterface {

    /** @var Entity\BasicInterface */
    private $_entity;

    /** @var array */
    private $_additionalParams;

    public function __construct(Entity\BasicInterface $entity, array $additionalParams = []) {
        $this->_entity = $entity;
        $this->_additionalParams = $additionalParams;
    }

    public function httpMethod(): string {
        return self::METHOD_POST;
    }

    public function parameters(): array {
        return array_merge([
            'fields' => $this->_entity->jsonSerialize(),
        ], $this->_additionalParams);
    }

    public function responseEntity(): ?string {
        return null;
    }
}
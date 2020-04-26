<?php

namespace Bitrix24ApiWrapper\Request;

use Bitrix24ApiWrapper\Entity;

abstract class AbstractSave implements BasicInterface {

    /** @var Entity\BasicInterface */
    private $_entity;

    /** @var array */
    private $_additionalParams;

    /**
     * @param Entity\BasicInterface $entity
     * @param array $additionalParams
     * @return static
     */
    public static function instance(Entity\BasicInterface $entity, array $additionalParams = []): self {
        return new static($entity, $additionalParams);
    }

    public function __construct(Entity\BasicInterface $entity, array $additionalParams = []) {
        $this->_entity = $entity;
        $this->_additionalParams = $additionalParams;
    }

    public function httpMethod(): string {
        return self::METHOD_POST;
    }

    public function parameters(): array {
        $parameters = ['fields' => $this->_entity];
        if ($this->_entity->id() !== null) {
            $parameters['id'] = $this->_entity->id();
        }
        if (count($this->_additionalParams) > 0) {
            $parameters['params'] = $this->_additionalParams;
        }
        return $parameters;
    }

    public function responseEntity(): ?string {
        return null;
    }
}
<?php

namespace Bitrix24ApiWrapper\Entity;

class AbstractBasic implements BasicInterface {

    /** @var array */
    private $_unfamiliarParameters = [];

    /**
     * @param string $name
     * @return mixed
     */
    public function unfamiliarParameter(string $name) {
        return $this->_unfamiliarParameters[$name] ?? null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void {
        $this->_unfamiliarParameters[$name] = $value;
    }

    public function jsonSerialize(): array {
        // todo: realize for send entities by api
        return [];
    }

    public function propertyConfiguration(string $propertyName): ?PropertyConfiguration\BasicInterface {
        return null;
    }
}
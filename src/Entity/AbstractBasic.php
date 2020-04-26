<?php

namespace Bitrix24ApiWrapper\Entity;

abstract class AbstractBasic implements BasicInterface {

    /** @var string */
    public $ID;

    /** @var array */
    private $_unfamiliarParameters = [];

    public function id(): ?string {
        return $this->ID;
    }

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
        $parameters = array_merge(get_object_vars($this), $this->_unfamiliarParameters);
        unset($parameters['_unfamiliarParameters']);
        return array_filter($parameters, function($propertyValue): bool {
            return $propertyValue !== null;
        });
    }

    public function propertyConfiguration(string $propertyName): ?PropertyConfiguration\BasicInterface {
        return null;
    }
}
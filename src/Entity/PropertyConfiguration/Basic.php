<?php

namespace Bitrix24ApiWrapper\Entity\PropertyConfiguration;

class Basic implements BasicInterface {

    /** @var string */
    private $_class;

    /** @var bool */
    private $_isEnumerable;

    public function __construct(string $class, bool $isEnumerable = false) {
        $this->_class = $class;
        $this->_isEnumerable = $isEnumerable;
    }

    public function class(): ?string {
        return $this->_class;
    }

    public function isEnumerable(): bool {
        return $this->_isEnumerable;
    }
}
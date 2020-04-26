<?php

namespace Bitrix24ApiWrapper\Test\Engine\Mock\Entity;

use Bitrix24ApiWrapper\Entity;

abstract class AbstractBasic implements Entity\BasicInterface {

    /** @var string */
    public $ID;

    public function id(): ?string {
        return $this->ID;
    }

    public function propertyConfiguration(string $propertyName): ?Entity\PropertyConfiguration\BasicInterface {
        return null;
    }

    public function jsonSerialize(): array {
        // todo: Implement jsonSerialize() method.
        return [];
    }
}
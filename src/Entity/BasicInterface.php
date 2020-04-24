<?php

namespace Bitrix24ApiWrapper\Entity;

interface BasicInterface extends \JsonSerializable {

    public function propertyConfiguration(string $propertyName): ?PropertyConfiguration\BasicInterface;
}
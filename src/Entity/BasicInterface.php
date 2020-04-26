<?php

namespace Bitrix24ApiWrapper\Entity;

interface BasicInterface extends \JsonSerializable {

    public function id(): ?string;

    public function propertyConfiguration(string $propertyName): ?PropertyConfiguration\BasicInterface;
}
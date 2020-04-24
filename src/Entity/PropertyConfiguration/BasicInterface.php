<?php

namespace Bitrix24ApiWrapper\Entity\PropertyConfiguration;

interface BasicInterface {

    public function class(): ?string;

    public function isEnumerable(): bool;
}
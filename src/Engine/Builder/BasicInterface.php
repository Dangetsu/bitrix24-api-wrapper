<?php

namespace Bitrix24ApiWrapper\Engine\Builder;

use Bitrix24ApiWrapper\Entity;

interface BasicInterface {

    public function buildEntity(string $className, array $data): Entity\BasicInterface;
}
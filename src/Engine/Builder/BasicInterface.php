<?php

namespace Bitrix24ApiWrapper\Engine\Builder;

use Bitrix24ApiWrapper\Entity;

interface BasicInterface {

    /**
     * @param string $className
     * @param array $entitiesData
     * @return Entity\BasicInterface[]
     */
    public function buildEntities(string $className, array $entitiesData): array;

    public function buildEntity(string $className, array $entityData): Entity\BasicInterface;
}
<?php

namespace Bitrix24ApiWrapper\Engine\Builder;

use Bitrix24ApiWrapper\Entity;

class Basic implements BasicInterface {

    /**
     * @param string $className
     * @param array $entitiesData
     * @return Entity\BasicInterface[]
     */
    public function buildEntities(string $className, array $entitiesData): array {
        return array_map(function (array $entityData) use($className): Entity\BasicInterface {
            return $this->buildEntity($className, $entityData);
        }, $entitiesData);
    }

    public function buildEntity(string $className, array $entityData): Entity\BasicInterface {
        $entity = new $className();
        $this->_setEntityProperties($entity, $entityData);
        return $entity;
    }

    protected function _setEntityProperties(Entity\BasicInterface $entity, array $data): void {
        foreach ($data as $propertyName => $value) {
            if ($value === null) {
                continue;
            }
            $propertyConfiguration = $entity->propertyConfiguration($propertyName);
            if ($propertyConfiguration === null) {
                $entity->$propertyName = $value;
            } elseif ($propertyConfiguration->isEnumerable()) {
                $entity->$propertyName = $this->buildEntities($propertyConfiguration->class(), $value);
            } else {
                $entity->$propertyName = $this->buildEntity($propertyConfiguration->class(), $value);
            }
        }
    }
}
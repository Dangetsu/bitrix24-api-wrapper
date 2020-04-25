<?php

namespace Bitrix24ApiWrapper\Test\Engine\Mock\Entity;

use Bitrix24ApiWrapper\Entity;

class SomeEntity extends AbstractBasic {

    /** @var int */
    public $INT;

    /** @var float */
    public $FLOAT;

    /** @var string */
    public $STRING;

    /** @var null */
    public $NULL;

    /** @var bool */
    public $BOOL;

    /** @var string[] */
    public $ARRAY;

    /** @var SomeObject */
    public $OBJECT;

    /** @var SomeObject[] */
    public $OBJECTS;

    public function propertyConfiguration(string $propertyName): ?Entity\PropertyConfiguration\BasicInterface {
        switch ($propertyName) {
            case 'OBJECT':
                return new Entity\PropertyConfiguration\Basic(SomeObject::class);
            case 'OBJECTS':
                return new Entity\PropertyConfiguration\Basic(SomeObject::class, true);
            default:
                return null;
        }
    }
}
<?php

namespace Bitrix24ApiWrapper\Test\Engine\Mock\Request;

use Bitrix24ApiWrapper\Test;
use Bitrix24ApiWrapper\Request;

class Some extends Request\Custom {

    public function responseEntity(): string {
        return Test\Engine\Mock\Entity\SomeEntity::class;
    }
}
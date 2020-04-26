<?php

namespace Bitrix24ApiWrapper\Test\Request\Mock\Request;

use Bitrix24ApiWrapper\Request;

class SomeDelete extends Request\AbstractDelete {

    public function apiMethod(): string {
        return 'entity.delete';
    }
}
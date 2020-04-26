<?php

namespace Bitrix24ApiWrapper\Test\Request\Mock\Request;

use Bitrix24ApiWrapper\Request;

class SomeSave extends Request\AbstractSave {

    public function apiMethod(): string {
        return 'entity.save';
    }
}
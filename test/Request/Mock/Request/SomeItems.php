<?php

namespace Bitrix24ApiWrapper\Test\Request\Mock\Request;

use Bitrix24ApiWrapper\Request;
use Bitrix24ApiWrapper\Test;

class SomeItems extends Request\AbstractItems {

    public function responseEntity(): ?string {
        return Test\Request\Mock\Entity\SomeEntity::class;
    }

    public function apiMethod(): string {
        return 'entity.list';
    }

    protected function _defaultSelects(): array {
        return ['PHONE', 'EMAIL'];
    }
}
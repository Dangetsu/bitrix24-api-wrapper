<?php

namespace Bitrix24ApiWrapper\Test\Engine\Mock\Extension;

use Bitrix24ApiWrapper\Engine;
use Bitrix24ApiWrapper\Test;

interface MockedEngineInterface extends Engine\BasicInterface {

    public function setMockResponse(Test\Engine\Entity\Mock $mock): void;
}
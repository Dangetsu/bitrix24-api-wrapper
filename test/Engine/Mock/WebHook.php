<?php

namespace Bitrix24ApiWrapper\Test\Engine\Mock;

use Bitrix24ApiWrapper\Engine;

class WebHook extends Engine\WebHook implements Extension\MockedEngineInterface {

    use Extension\MockedEngineTrait;
}
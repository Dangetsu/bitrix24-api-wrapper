<?php

namespace Bitrix24ApiWrapper\Engine;

use Bitrix24ApiWrapper\Request;

interface BasicInterface {

    /**
     * @param Request\BasicInterface $request
     * @return mixed
     */
    public function execute(Request\BasicInterface $request);
}
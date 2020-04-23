<?php

namespace Bitrix24ApiWrapper\Engine;

interface BasicInterface {

    /**
     * @param string $apiMethod
     * @param array $parameters
     * @return mixed
     */
    public function get(string $apiMethod, array $parameters = []);

    /**
     * @param string $apiMethod
     * @param array $parameters
     * @return mixed
     */
    public function post(string $apiMethod, array $parameters = []);
}
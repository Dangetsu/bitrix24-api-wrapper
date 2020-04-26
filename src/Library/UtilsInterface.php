<?php

namespace Bitrix24ApiWrapper\Library;

interface UtilsInterface {

    public function isAssocArray(array $array): bool;

    public function jsonDecode(?string $json): ?array;
}
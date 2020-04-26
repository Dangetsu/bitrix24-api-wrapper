<?php

namespace Bitrix24ApiWrapper\Library;

interface UtilsInterface {

    /**
     * @param mixed $value
     * @return bool
     */
    public function isNumericInt($value): bool;

    /**
     * @param mixed $value
     * @return bool
     */
    public function isPositiveNumericInt($value): bool;

    public function isAssocArray(array $array): bool;

    public function jsonDecode(?string $json): ?array;
}
<?php

namespace Bitrix24ApiWrapper\Library;

class Utils {

    public function isAssocArray(array $array): bool {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    public function jsonDecode(?string $json): ?array {
        if ($json === null || $json === '') {
            return null;
        }
        $result = json_decode($json, true);
        $this->_checkJsonError();
        return $result;
    }

    private function _checkJsonError(): void {
        $error = json_last_error();
        switch ($error) {
            case JSON_ERROR_NONE:
                break;
            case JSON_ERROR_DEPTH:
                throw new Exception\JSON('Max stack depth exceeded', $error);
            case JSON_ERROR_STATE_MISMATCH:
                throw new Exception\JSON('Invalid or malformed JSON', $error);
            case JSON_ERROR_CTRL_CHAR:
                throw new Exception\JSON('Incorrect control character', $error);
            case JSON_ERROR_SYNTAX:
                throw new Exception\JSON('Syntax error', $error);
            case JSON_ERROR_UTF8:
                throw new Exception\JSON('Incorrect UTF-8 symbol, maybe encoding error', $error);
            default:
                throw new Exception\JSON('Unknown error', $error);
        }
    }
}
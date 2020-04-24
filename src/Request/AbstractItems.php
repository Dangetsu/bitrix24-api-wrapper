<?php

namespace Bitrix24ApiWrapper\Request;

abstract class AbstractItems implements BasicInterface {

    private const SELECT_FIELDS_MASK_ALL    = '*';
    private const SELECT_FIELDS_MASK_CUSTOM = 'UF_*';

    /** @var array */
    private $_filter;

    /** @var array */
    private $_order;

    /** @var array */
    private $_select;

    public function __construct(array $filter = [], array $order = [], array $select = []) {
        $this->_filter = $filter;
        $this->_order  = $order;
        $this->_select = $select;
    }

    abstract public function responseEntity(): ?string;

    abstract public function apiMethod(): string;

    public function httpMethod(): string {
        return self::METHOD_GET;
    }

    public function parameters(): array {
        $parameters = [
            'select' => count($this->_select) > 0 ? $this->_select : $this->_allSelects(),
        ];
        if (count($this->_filter) > 0) {
            $parameters['filter'] = $this->_filter;
        }
        if (count($this->_order) > 0) {
            $parameters['order'] = $this->_filter;
        }
        return $parameters;
    }

    protected function _defaultSelects(): array {
        return [];
    }

    private function _allSelects(): array {
        return array_merge([self::SELECT_FIELDS_MASK_ALL, self::SELECT_FIELDS_MASK_CUSTOM], $this->_defaultSelects());
    }
}
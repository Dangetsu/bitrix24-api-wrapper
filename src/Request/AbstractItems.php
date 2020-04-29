<?php

namespace Bitrix24ApiWrapper\Request;

abstract class AbstractItems implements BasicInterface {

    private const SELECT_FIELDS_MASK_ALL    = '*';
    private const SELECT_FIELDS_MASK_CUSTOM = 'UF_*';

    private const PARAM_START_LOAD_ONLY_FIRST_PAGE = '-1';

    /** @var array */
    private $_filter;

    /** @var array */
    private $_order;

    /** @var array */
    private $_select;

    /** @var bool */
    private $_isLoadOnlyFirstPage;

    /**
     * In batch request load only first page
     *
     * @param array $filter
     * @param array $order
     * @param array $select
     * @return static
     */
    public static function all(array $filter = [], array $order = [], array $select = []): self {
        return new static($filter, $order, $select);
    }

    /**
     * @param array $filter
     * @param array $order
     * @param array $select
     * @return static
     */
    public static function firstPage(array $filter = [], array $order = [], array $select = []): self {
        return new static($filter, $order, $select, true);
    }

    public function __construct(array $filter = [], array $order = [], array $select = [], bool $isLoadOnlyFirstPage = false) {
        $this->_filter = $filter;
        $this->_order = $order;
        $this->_select = $select;
        $this->_isLoadOnlyFirstPage = $isLoadOnlyFirstPage;
    }

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
            $parameters['order'] = $this->_order;
        }
        if ($this->_isLoadOnlyFirstPage) {
            $parameters['start'] = self::PARAM_START_LOAD_ONLY_FIRST_PAGE;
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
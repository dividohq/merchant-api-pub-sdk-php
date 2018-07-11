<?php

namespace Divido\MerchantSDK\Handlers;

/**
 * Class ApiRequestOptions
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ApiRequestOptions
{
    /**
     * @var int
     */
    private $page = 1;

    /**
     * @var string
     */
    private $sort;

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var bool
     */
    private $paginated = true;

    /**
     * ApiRequestOptions constructor.
     */
    public function __construct()
    {
        ;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return ApiRequestOptions
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     * @return ApiRequestOptions
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return ApiRequestOptions
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPaginated()
    {
        return $this->paginated;
    }

    /**
     * @param bool $paginated
     * @return ApiRequestOptions
     */
    public function setPaginated($paginated)
    {
        $this->paginated = $paginated;
        return $this;
    }
}

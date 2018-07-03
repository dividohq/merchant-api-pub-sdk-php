<?php


namespace Divido\MerchantSDK\Handlers;


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
    public function setPage(int $page)
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
    public function setSort(string $sort)
    {
        $this->sort = $sort;
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
    public function setPaginated(bool $paginated)
    {
        $this->paginated = $paginated;
        return $this;
    }


}
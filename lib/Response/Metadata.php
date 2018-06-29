<?php


namespace Divido\MerchantSDK\Response;


class Metadata
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $totalPages;

    /**
     * @var int
     */
    private $resourcesPerPage;

    /**
     * @var int
     */
    private $totalResourceCount;

    public function __construct($page = null, $totalPages = null, $resourcesPerPage = null, $totalResourceCount = null)
    {
        $this->page = $page;
        $this->totalPages = $totalPages;
        $this->resourcesPerPage = $resourcesPerPage;
        $this->totalResourceCount = $totalResourceCount;
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
     * @return Metadata
     */
    public function setPage(int $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     * @return Metadata
     */
    public function setTotalPages(int $totalPages)
    {
        $this->totalPages = $totalPages;
        return $this;
    }

    /**
     * @return int
     */
    public function getResourcesPerPage()
    {
        return $this->resourcesPerPage;
    }

    /**
     * @param int $resourcesPerPage
     * @return Metadata
     */
    public function setResourcesPerPage(int $resourcesPerPage)
    {
        $this->resourcesPerPage = $resourcesPerPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalResourceCount()
    {
        return $this->totalResourceCount;
    }

    /**
     * @param int $totalResourceCount
     * @return Metadata
     */
    public function setTotalResourceCount(int $totalResourceCount)
    {
        $this->totalResourceCount = $totalResourceCount;
        return $this;
    }


}
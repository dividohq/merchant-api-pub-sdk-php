<?php

namespace Divido\MerchantSDK\Response;

/**
 * Class Metadata
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
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

    /**
     * Metadata constructor.
     *
     * @param int $page
     * @param int $totalPages
     * @param int $resourcesPerPage
     * @param int $totalResourceCount
     */
    public function __construct($page = null, $totalPages = null, $resourcesPerPage = null, $totalResourceCount = null)
    {
        $this->page = $page;
        $this->totalPages = $totalPages;
        $this->resourcesPerPage = $resourcesPerPage;
        $this->totalResourceCount = $totalResourceCount;
    }

    /**
     * Returns the requested page
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set the page
     *
     * @param int $page
     * @return Metadata
     */
    public function setPage(int $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Returns the total pages
     *
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Set the total pages
     *
     * @param int $totalPages
     * @return Metadata
     */
    public function setTotalPages(int $totalPages)
    {
        $this->totalPages = $totalPages;
        return $this;
    }

    /**
     * Returns the resources per page
     *
     * @return int
     */
    public function getResourcesPerPage()
    {
        return $this->resourcesPerPage;
    }

    /**
     * Set the resources per page
     *
     * @param int $resourcesPerPage
     * @return Metadata
     */
    public function setResourcesPerPage(int $resourcesPerPage)
    {
        $this->resourcesPerPage = $resourcesPerPage;
        return $this;
    }

    /**
     * Returns the resources count
     *
     * @return int
     */
    public function getTotalResourceCount()
    {
        return $this->totalResourceCount;
    }

    /**
     * Set the total resources count
     *
     * @param int $totalResourceCount
     * @return Metadata
     */
    public function setTotalResourceCount(int $totalResourceCount)
    {
        $this->totalResourceCount = $totalResourceCount;
        return $this;
    }
}

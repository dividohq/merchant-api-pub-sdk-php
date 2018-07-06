<?php

namespace Divido\MerchantSDK\Response;

use GuzzleHttp\Psr7\Response;

/**
 * Class ResponseWrapper
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ResponseWrapper
{
    /**
     * @var array
     */
    private $resources;

    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @var Response
     */
    private $rawResponse;

    /**
     * ResponseWrapper constructor.
     *
     * @param array $resources
     * @param int $page
     * @param int $totalPages
     * @param int $resourcesPerPage
     * @param int $totalResourceCount
     */
    public function __construct($resources = [], $metadata = null, $rawResponse = null)
    {
        $this->resources = $resources;
        $this->metadata = $metadata;
        $this->rawResponse = $rawResponse;
    }

    /**
     * Get resources.
     *
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Set resources.
     *
     * @param array $resources
     * @return ResponseWrapper
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
        return $this;
    }

    /**
     * Get metadata.
     *
     * @return Metadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set metadata.
     *
     * @param Metadata $metadata
     * @return ResponseWrapper
     */
    public function setMetadata(Metadata $metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * Get raw response.
     *
     * @return Response
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * Set raw response.
     *
     * @param Response $rawResponse
     * @return ResponseWrapper
     */
    public function setRawResponse(Response $rawResponse)
    {
        $this->rawResponse = $rawResponse;
        return $this;
    }
}

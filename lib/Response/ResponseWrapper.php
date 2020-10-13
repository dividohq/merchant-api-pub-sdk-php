<?php

namespace Divido\MerchantSDK\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseWrapper
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
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
     * @var ResponseInterface
     */
    private $rawResponse;

    /**
     * @param array $resources
     * @param mixed $metadata
     * @param ResponseInterface $rawResponse
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
     * @return ResponseInterface
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * Set raw response.
     *
     * @param ResponseInterface $rawResponse
     * @return ResponseWrapper
     */
    public function setRawResponse(ResponseInterface $rawResponse)
    {
        $this->rawResponse = $rawResponse;

        return $this;
    }
}

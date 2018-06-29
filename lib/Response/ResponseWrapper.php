<?php


namespace Divido\MerchantSDK\Response;


use GuzzleHttp\Psr7\Response;

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

    public function __construct($resources = [], $metadata = null, $rawResponse = null)
    {
        $this->resources = $resources;
        $this->metadata = $metadata;
        $this->rawResponse = $rawResponse;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param array $resources
     * @return ResponseWrapper
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
        return $this;
    }

    /**
     * @return Metadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param Metadata $metadata
     * @return ResponseWrapper
     */
    public function setMetadata(Metadata $metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @return Response
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @param Response $rawResponse
     * @return ResponseWrapper
     */
    public function setRawResponse(Response $rawResponse)
    {
        $this->rawResponse = $rawResponse;
        return $this;
    }


}
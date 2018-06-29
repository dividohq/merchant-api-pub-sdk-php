<?php


namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Response\Metadata;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractHttpHandler
{
    /**
     * @var HttpClientWrapper
     */
    protected $httpClientWrapper;

    final public function __construct(HttpClientWrapper $httpClientWrapper)
    {
        $this->httpClientWrapper = $httpClientWrapper;
    }

    /**
     * Merge any included data in JSON API spec into main object
     *
     * @param object $original The original JSON API response
     */
    protected function mergeIncludedData(&$original)
    {
        foreach ($original->data as &$resource) {
            if (!property_exists($resource, 'relationships')) {
                continue;
            }
            $this->iterateRelationshipsAndApplyIncludedData($resource, $original);
        }
    }

    /**
     * Iterate over relationships of a single to apply included data
     *
     * @param object $resource The parent resource to iterate over child relationhsis
     * @param object $original The original JSON API response
     */
    private function iterateRelationshipsAndApplyIncludedData(&$resource, $original)
    {
        foreach ($resource->relationships as $type => &$data) {
            if (is_object($data->data)) {
                $this->addIncludeDataToModel($original->included, $data->data);
            } elseif (is_array($data->data)) {
                foreach ($data->data as &$nestedData) {
                    $this->addIncludeDataToModel($original->included, $nestedData);
                }
            }
        }
    }

    /**
     * Find included data and add to resource model
     *
     * @param array $included The included resource models
     * @param object $model The parent data model to add included data into
     */
    private function addIncludeDataToModel($included, &$model)
    {
        foreach ($included as $inclusion) {
            if ($inclusion->type === $model->type
                && $inclusion->id === $model->id)
            {
                $model->attributes = $inclusion->attributes;
                break;
            }
        }
    }

    /**
     * @param array $requested
     * @param array $allowed
     *
     * @throws \Exception
     */
    protected function validateInclusions(array $requested, array $allowed)
    {
        foreach ($requested as $test) {
            if (!in_array($test, $allowed)) {
                throw new \Exception('Included property not allowed on this resource');
            }
        }
    }

    protected function parseJsonApiResourceResponse(ResponseInterface $response)
    {
        $json = json_decode($response->getBody()->getContents());

        // Sanitise for single object resources
        if (!is_array($json->data)) {
            $json->data = [$json->data];
        }

        // Merge any included data into parent resource(s)
        if (property_exists($json, 'included')) {
            $this->mergeIncludedData($json);
        }


        // Rewind the response
        $response->getBody()->rewind();

        $httpResponseWrapper = new ResponseWrapper(
            $json->data,
            new Metadata(
                $json->meta->current_page,
                $json->meta->last_page,
                $json->meta->per_page,
                $json->meta->total
            ),
            $response
        );

        return $httpResponseWrapper;
    }

    public function getFullResourceCollection($callback, ...$args)
    {
        $resources = [];
        $hasMorePages = true;
        $currentPage = 1;

        while ($hasMorePages)
        {
            $methodArgs = array_merge([$currentPage], $args);

            /** @var ResponseWrapper $response */
            $response = call_user_func([$this, $callback], ...$methodArgs);
            if (count($response->getResources()) > 1) {
                $resources = array_merge($resources, $response->getResources());
            }

            $hasMorePages = $currentPage < $response->getMetadata()->getTotalPages();
            $currentPage++;
        }

        $httpResponseWrapper = new ResponseWrapper(
            $resources,
            new Metadata(1, 1, count($resources), count($resources)),
            null
        );

        return $httpResponseWrapper;
    }

    /**
     * @param $callback
     * @param array $args
     * @return \Generator
     */
    public function yieldFullResourceCollection($callback, ...$args)
    {
        $hasMorePages = true;
        $currentPage = 1;

        while ($hasMorePages)
        {
            $methodArgs = array_merge([$currentPage], $args);

            /** @var ResponseWrapper $response */
            $response = call_user_func([$this, $callback], ...$methodArgs);
            foreach ($response->getResources() as $resource) {
                yield $resource;
            }

            $hasMorePages = $currentPage < $response->getMetadata()->getTotalPages();
            $currentPage++;
        }

    }


}
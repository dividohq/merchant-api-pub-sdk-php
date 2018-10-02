<?php

namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\Exceptions\MerchantApiBadResponseException;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Response\Metadata;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractHttpHandler
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
abstract class AbstractHttpHandler
{
    /**
     * @var HttpClientWrapper
     */
    protected $httpClientWrapper;

    /**
     * Create a new abstract http handler instance.
     *
     * @param HttpClientWrapper $httpClientWrapper
     * @return void
     */
    final public function __construct(HttpClientWrapper $httpClientWrapper)
    {
        $this->httpClientWrapper = $httpClientWrapper;
    }

    /**
     * Parse response
     *
     * @param ResponseInterface $response
     * @return ResponseWrapper
     */
    public function parseResponse(ResponseInterface $response)
    {
        $json = json_decode($response->getBody()->getContents());

        if (isset($json->error)) {
            throw new MerchantApiBadResponseException(
                $json->message,
                $json->code,
                $json->context
            );
        }

        // Sanitise for single object resources
        if (!is_array($json->data)) {
            $json->data = [$json->data];
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

    /**
     * Get the full resource collection.
     *
     * @param string $callback
     * @param ApiRequestOptions $options
     * @param mixed $args
     * @return ResponseWrapper
     */
    public function getFullResourceCollection($callback, ApiRequestOptions $options, ...$args)
    {
        $resources = [];
        $hasMorePages = true;
        $options->setPage(1);

        while ($hasMorePages)
        {
            /** @var ResponseWrapper $response */
            $response = call_user_func([$this, $callback], $options, ...$args);

            if (count($response->getResources()) > 0) {
                $resources = array_merge($resources, $response->getResources());
            }

            $hasMorePages = $options->getPage() < $response->getMetadata()->getTotalPages();
            $options->setPage($options->getPage() + 1);
        }

        $httpResponseWrapper = new ResponseWrapper(
            $resources,
            new Metadata(1, 1, count($resources), count($resources)),
            null
        );

        return $httpResponseWrapper;
    }

    /**
     * Get the full resource collection.
     *
     * @param $callback
     * @param ApiRequestOptions $options
     * @param array $args
     * @return \Generator
     */
    public function yieldFullResourceCollection($callback, $options, ...$args)
    {
        $hasMorePages = true;
        $options->setPage(1);

        while ($hasMorePages)
        {
            /** @var ResponseWrapper $response */
            $response = call_user_func([$this, $callback], $options, ...$args);
            foreach ($response->getResources() as $resource) {
                yield $resource;
            }

            $hasMorePages = $options->getPage() < $response->getMetadata()->getTotalPages();
            $options->setPage($options->getPage() + 1);
        }
    }

}

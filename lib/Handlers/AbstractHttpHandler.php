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

    protected function parseJsonApiResourceResponse(ResponseInterface $response)
    {
        $json = json_decode($response->getBody()->getContents());

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
     * @param $callback
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

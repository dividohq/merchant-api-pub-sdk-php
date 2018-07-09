<?php

namespace Divido\MerchantSDK\Handlers\Channels;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * Class Handler
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class Handler extends AbstractHttpHandler
{
    /**
     * Get channels as a collection, either a specific page or all
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getChannels(ApiRequestOptions $options)
    {
        if ($options->isPaginated() === false) {
            return $this->getAllChannels($options);
        }

        return $this->getChannelsByPage($options);
    }

    /**
     * Yield channels one at a time, either from a specific page or all
     *
     * @param ApiRequestOptions $options
     * @return \Generator
     */
    public function yieldChannels(ApiRequestOptions $options)
    {
        if ($options->isPaginated() === false) {
            foreach ($this->yieldAllChannels($options) as $channel) {
                yield $channel;
            }
            return;
        }

        $responseWrapper = $this->getChannelsByPage($options);
        foreach ($responseWrapper->getResources() as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all channels and yield one at a time using a generator
     *
     * @param ApiRequestOptions $options
     * @return \Generator
     */
    public function yieldAllChannels(ApiRequestOptions $options)
    {
        foreach ($this->yieldFullResourceCollection('getChannelsByPage', $options) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all channels by page.
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getChannelsByPage(ApiRequestOptions $options)
    {
        $path = vsprintf('%s', [
            'channels',
        ]);

        $query = [
            'page' => $options->getPage(),
            'sort' => $options->getSort(),
        ];

        $response = $this->httpClientWrapper->request('get', $path, $query);
        $parsed = $this->parseResponse($response);

        return $parsed;
    }

    /**
     * Get all channels in a single array
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getAllChannels(ApiRequestOptions $options)
    {
        return $this->getFullResourceCollection('getChannelsByPage', $options);
    }
}

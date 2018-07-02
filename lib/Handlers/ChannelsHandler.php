<?php

namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * ChannelsHandler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ChannelsHandler extends AbstractHttpHandler
{
    /**
     * Get channels by a specific page number
     *
     * @param int $page The page to retrieve
     * @param string $sort
     * @return ResponseWrapper
     */
    public function getChannelsByPage($page = 1, $sort = '')
    {
        $query = [
            'page' => $page,
            'sort' => $sort,
        ];

        $response = $this->httpClientWrapper->request('get', 'channels', $query);
        $parsed = $this->parseJsonApiResourceResponse($response);

        return $parsed;
    }

    /**
     * Get all channels in a single array
     *
     * @return ResponseWrapper
     */
    public function getAllChannels()
    {
        return $this->getFullResourceCollection('getChannelsByPage');
    }

    /**
     * Get all channels and yield one plan at a time using a generator
     *
     * @return \Generator
     */
    public function yieldAllChannels()
    {
        foreach ($this->yieldFullResourceCollection('getChannelsByPage') as $resource) {
            yield $resource;
        }
    }


}

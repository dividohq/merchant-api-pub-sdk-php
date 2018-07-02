<?php

namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\Models\Settlement;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * SettlementsHandler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class SettlementsHandler extends AbstractHttpHandler
{
    /**
     * Get Settlements by a specific page number
     *
     * @param int $page The page to retrieve
     * @param string $sort
     * @return ResponseWrapper
     */
    public function getSettlementsByPage($page = 1, $sort = '')
    {
        $query = [
            'page' => $page,
            'sort' => $sort,
        ];

        $response = $this->httpClientWrapper->request('get', 'settlements', $query);
        $parsed = $this->parseJsonApiResourceResponse($response);

        return $parsed;
    }

    /**
     * Get all Settlements in a single array
     *
     * @return ResponseWrapper
     */
    public function getAllSettlements()
    {
        return $this->getFullResourceCollection('getSettlementsByPage');
    }

    /**
     * Get all Settlements and yield one plan at a time using a generator
     *
     * @return \Generator
     */
    public function yieldAllSettlements()
    {
        foreach ($this->yieldFullResourceCollection('getSettlementsByPage') as $resource) {
            yield $resource;
        }
    }

    /**
     * Get single settlement by id
     *
     * @return ResponseWrapper
     */
    public function getSingleSettlement(Settlement $settlement)
    {
        $path = vsprintf('%s/%s', [
            'settlements',
            $settlement->getId(),
        ]);

        return $this->httpClientWrapper->request('get', $path);
    }
}

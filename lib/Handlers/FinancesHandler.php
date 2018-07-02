<?php

namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * FinancesHandler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class FinancesHandler extends AbstractHttpHandler
{
    /**
     * Get finance plans by a specific page number
     *
     * @param int $page The page to retrieve
     * @param string $sort
     * @return ResponseWrapper
     */
    public function getPlansByPage($page = 1, $sort = '')
    {
        $query = [
            'page' => $page,
            'sort' => $sort,
        ];

        $response = $this->httpClientWrapper->request('get', 'finance-plans', $query);
        $parsed = $this->parseJsonApiResourceResponse($response);

        return $parsed;
    }

    /**
     * Get all plans in a single array
     *
     * @return ResponseWrapper
     */
    public function getAllPlans()
    {
        return $this->getFullResourceCollection('getPlansByPage');
    }

    /**
     * Get all plans and yield one plan at a time using a generator
     *
     * @return \Generator
     */
    public function yieldAllPlans()
    {
        foreach ($this->yieldFullResourceCollection('getPlansByPage') as $resource) {
            yield $resource;
        }
    }
}

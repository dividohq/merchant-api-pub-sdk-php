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
     * @param array $included A list of inclusions to retrieve
     * @param string $sort
     * @return ResponseWrapper
     */
    public function getPlansByPage($page = 1, $included = [], $sort = '')
    {

        $query = [
            'page' => $page,
            'include' => implode(',', $included),
            'sort' => $sort,
        ];

        $response = $this->httpClientWrapper->request('get', 'finance-plans', $query);
        $parsed = $this->parseJsonApiResourceResponse($response);

        return $parsed;
    }


    /**
     * Get all plans in a single array
     *
     * @param array $included
     *
     * @return ResponseWrapper
     */
    public function getAllPlans($included = [])
    {
        return $this->getFullResourceCollection('getPlansByPage', $included);
    }

    /**
     * Get all plans and yield one plan at a time using a generator
     *
     * @param array $included
     * @return \Generator
     */
    public function yieldAllPlans($included = [])
    {
        foreach ($this->yieldFullResourceCollection('getPlansByPage', $included) as $resource) {
            yield $resource;
        }
    }


}
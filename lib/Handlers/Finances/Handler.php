<?php

namespace Divido\MerchantSDK\Handlers\Finances;

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
     * Get plans as a collection, either a specific page or all
     *
     * @param ApiRequestOptions $options API Request options
     * @return ResponseWrapper
     */
    public function getPlans(ApiRequestOptions $options)
    {
        if ($options->isPaginated() === false) {
            return $this->getAllPlans($options);
        }

        return $this->getPlansByPage($options);
    }

    /**
     * Yield plans one at a time, either from a specific page or all
     *
     * @param ApiRequestOptions $options API Request options
     * @return \Generator
     */
    public function yieldPlans(ApiRequestOptions $options)
    {
        if ($options->isPaginated() === false) {
            foreach ($this->yieldAllPlans($options) as $plan) {
                yield $plan;
            }
            return;
        }

        $responseWrapper = $this->getPlansByPage($options);
        foreach ($responseWrapper->getResources() as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all and yield one plan at a time using a generator
     *
     * @param ApiRequestOptions $options API Request options
     * @return \Generator
     */
    public function yieldAllPlans(ApiRequestOptions $options)
    {
        foreach ($this->yieldFullResourceCollection('getPlansByPage', $options) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all plans by page.
     *
     * @param ApiRequestOptions $options API Request options
     * @return \ResponseWrapper
     */
    public function getPlansByPage(ApiRequestOptions $options)
    {
        $path = vsprintf('%s', [
            'finance-plans',
        ]);

        $query = [
            'page' => $options->getPage(),
            'sort' => $options->getSort(),
        ];

        $response = $this->httpClientWrapper->request('get', $path, $query);
        $parsed = $this->parseJsonApiResourceResponse($response);

        return $parsed;
    }

    /**
     * Get all in a single array
     *
     * @param ApiRequestOptions $options API Request options
     * @return ResponseWrapper
     */
    public function getAllPlans(ApiRequestOptions $options)
    {
        return $this->getFullResourceCollection('getPlansByPage', $options);
    }
}

<?php

namespace Divido\MerchantSDK\Handlers\Settlements;

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
     * Get settlements as a collection, either a specific page or all
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getSettlements(ApiRequestOptions $options)
    {
        if ($options->isPaginated() === false) {
            return $this->getAllSettlements($options);
        }

        return $this->getSettlementsByPage($options);
    }

    /**
     * Yield settlements one at a time, either from a specific page or all
     *
     * @param ApiRequestOptions $options
     * @return \Generator
     */
    public function yieldSettlements(ApiRequestOptions $options)
    {
        if ($options->isPaginated() === false) {
            foreach ($this->yieldAllSettlements($options) as $settlement) {
                yield $settlement;
            }
            return;
        }

        $responseWrapper = $this->getSettlementsByPage($options);
        foreach ($responseWrapper->getResources() as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all and yield one plan at a time using a generator
     *
     * @param ApiRequestOptions $options
     * @return \Generator
     */
    public function yieldAllSettlements(ApiRequestOptions $options)
    {
        foreach ($this->yieldFullResourceCollection('getSettlementsByPage', $options) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get settlements by page.
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getSettlementsByPage(ApiRequestOptions $options)
    {
        $path = vsprintf('%s', [
            'settlements',
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
     * Get all in a single array
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getAllSettlements(ApiRequestOptions $options)
    {
        return $this->getFullResourceCollection('getSettlementsByPage', $options);
    }

    /**
     * Get single settlement by id
     *
     * @return ResponseWrapper
     */
    public function getSingleSettlement($settlementId)
    {
        $path = vsprintf('%s/%s', [
            'settlements',
            $settlementId,
        ]);

        return $this->httpClientWrapper->request('get', $path);
    }
}

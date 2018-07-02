<?php

namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * ApplicationRefundsHandler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ApplicationRefundsHandler extends AbstractHttpHandler
{
    /**
     * @var string
     */
    private $applicationId;

    /**
     * Chain the application first to be able to use the id in subsequent requests.
     *
     * @param string $applicationId
     * @return ApplicationActivationsHandler
     */
    public function withApplicationId($applicationId)
    {
        $this->applicationId = $applicationId;

        return $this;
    }

    /**
     * Get applications by a specific page number
     *
     * @param int $page The page to retrieve
     * @param string $sort
     * @return ResponseWrapper
     */
    public function getApplicationRefundsByPage($page = 1, $sort = '')
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $this->applicationId,
            'refunds',
        ]);

        $query = [
            'page' => $page,
            'sort' => $sort,
        ];

        $response = $this->httpClientWrapper->request('get', $path, $query);
        $parsed = $this->parseJsonApiResourceResponse($response);

        return $parsed;
    }


    /**
     * Get all applications in a single array
     *
     * @return ResponseWrapper
     */
    public function getAllApplicationRefunds()
    {
        return $this->getFullResourceCollection('getApplicationRefundsByPage', $this->applicationId);
    }

    /**
     * Get all applications and yield one plan at a time using a generator
     *
     * @return \Generator
     */
    public function yieldAllApplicationRefunds()
    {
        foreach ($this->yieldFullResourceCollection('getApplicationRefundsByPage', $this->applicationId) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get single refund by id
     *
     * @return ResponseWrapper
     */
    public function getSingleApplicationRefund($refundId)
    {
        $path = vsprintf('%s/%s/%s/%s', [
            'applications',
            $this->applicationId,
            'refunds',
            $refundId,
        ]);

        return $this->httpClientWrapper->request('get', $path);
    }

    /**
     * Create a refund
     *
     * @return ResponseWrapper
     */
    public function createApplicationRefund($payload)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $this->applicationId,
            'refunds',
        ]);

        return $this->httpClientWrapper->request('post', $path, [], [], $payload);
    }
}

<?php

namespace Divido\MerchantSDK\Handlers\ApplicationRefunds;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationRefund;
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
     * Get application refunds as a collection, either a specific page or all
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return ResponseWrapper
     */
    public function getApplicationRefunds(ApiRequestOptions $options, Application $application)
    {
        if ($options->isPaginated() === false) {
            return $this->getAllApplicationRefunds($options, $application);
        }

        return $this->getApplicationRefundsByPage($options, $application);
    }

    /**
     * Yield application refunds one at a time, either from a specific page or all
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return \Generator
     */
    public function yieldApplicationRefunds(ApiRequestOptions $options, Application $application)
    {
        if ($options->isPaginated() === false) {
            foreach ($this->yieldAllApplicationRefunds($options, $application) as $refund) {
                yield $refund;
            }
            return;
        }

        $responseWrapper = $this->getApplicationRefundsByPage($options, $application);
        foreach ($responseWrapper->getResources() as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all refunds and yield one at a time using a generator
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return \Generator
     */
    public function yieldAllApplicationRefunds(ApiRequestOptions $options, Application $application)
    {
        foreach ($this->yieldFullResourceCollection('getApplicationRefundsByPage', $options, $application) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all application refunds by page.
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return ResponseWrapper
     */
    public function getApplicationRefundsByPage(ApiRequestOptions $options, Application $application)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'refunds',
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
     * Get all applications in a single array
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return ResponseWrapper
     */
    public function getAllApplicationRefunds(ApiRequestOptions $options, Application $application)
    {
        return $this->getFullResourceCollection('getApplicationRefundsByPage', $options, $application);
    }

    /**
     * Get single refund by id
     *
     * @param Application $application
     * @param string $refundId
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getSingleApplicationRefund(Application $application, $refundId)
    {
        $path = vsprintf('%s/%s/%s/%s', [
            'applications',
            $application->getId(),
            'refunds',
            $refundId,
        ]);

        return $this->httpClientWrapper->request('get', $path);
    }

    /**
     * Create an application refund
     *
     * @param Application $application
     * @param ApplicationRefund $applicationRefund
     * @return \GuzzleHttp\Psr7\Response
     */
    public function createApplicationRefund(Application $application, ApplicationRefund $applicationRefund)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'refunds',
        ]);

        return $this->httpClientWrapper->request('post', $path, [], [], $applicationRefund->getJsonPayload());
    }
}

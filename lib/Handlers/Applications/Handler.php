<?php

namespace Divido\MerchantSDK\Handlers\Applications;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * Handler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class Handler extends AbstractHttpHandler
{
    /**
     * Get application refunds as a collection, either a specific page or all
     *
     * @param ApiRequestOptions $options API Request options
     * @param Application $application
     * @return ResponseWrapper
     */
    public function getApplications(ApiRequestOptions $options)
    {
        if ($options->isPaginated() === false) {
            return $this->getAllApplications($options);
        }

        return $this->getApplicationsByPage($options);
    }

    /**
     * Yield application refunds one at a time, either from a specific page or all
     *
     * @param ApiRequestOptions $options API Request options
     * @param Application $application
     * @return \Generator
     */
    public function yieldApplications(ApiRequestOptions $options)
    {
        if ($options->isPaginated() === false) {
            foreach ($this->yieldAllApplications($options) as $application) {
                yield $application;
            }
            return;
        }

        $responseWrapper = $this->getApplicationsByPage($options);
        foreach ($responseWrapper->getResources() as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all applications and yield one plan at a time using a generator
     *
     * @param ApiRequestOptions $options API Request options
     * @param Application $application
     * @return \Generator
     */
    protected function yieldAllApplications(ApiRequestOptions $options)
    {
        foreach ($this->yieldFullResourceCollection('getApplicationsByPage', $options) as $resource) {
            yield $resource;
        }
    }

    protected function getApplicationsByPage(ApiRequestOptions $options)
    {
        $path = vsprintf('%s', [
            'applications',
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
     * Get all applications in a single array
     *
     * @param ApiRequestOptions $options API Request options
     * @param Application $application
     * @return ResponseWrapper
     */
    protected function getAllApplications(ApiRequestOptions $options)
    {
        return $this->getFullResourceCollection('getApplicationsByPage', $options);
    }

    /**
     * Get single refund by id
     *
     * @return ResponseWrapper
     */
    public function getSingleApplication($applicationId)
    {
        $path = vsprintf('%s/%s', [
            'applications',
            $applicationId,
        ]);

        return $this->httpClientWrapper->request('get', $path);
    }

    /**
     * Create an refund
     *
     * @return ResponseWrapper
     */
    public function createApplication(Application $application)
    {
        $path = vsprintf('%s', [
            'applications',
        ]);

        return $this->httpClientWrapper->request('post', $path, [], [], $application->getJsonPayload());
    }

    /**
     * Update an application
     *
     * @return ResponseWrapper
     */
    public function updateApplication(Application $application)
    {
        $path = vsprintf('%s/%s', [
            'applications',
            $application->getId(),
        ]);

        return $this->httpClientWrapper->request('patch', $path, [], [], $application->getJsonPayload());
    }
}

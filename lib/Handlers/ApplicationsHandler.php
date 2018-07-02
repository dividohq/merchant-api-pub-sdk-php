<?php

namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * ApplicationsHandler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ApplicationsHandler extends AbstractHttpHandler
{
    /**
     * Get applications by a specific page number
     *
     * @param int $page The page to retrieve
     * @param string $sort
     * @return ResponseWrapper
     */
    public function getApplicationsByPage($page = 1, $sort = '', $filters = [])
    {
        $query = [
            'page' => $page,
            'sort' => $sort,
            'filter' => $filters,
        ];

        $response = $this->httpClientWrapper->request('get', 'applications', $query);
        $parsed = $this->parseJsonApiResourceResponse($response);

        return $parsed;
    }

    /**
     * Get all applications in a single array
     *
     * @return ResponseWrapper
     */
    public function getAllApplications()
    {
        return $this->getFullResourceCollection('getApplicationsByPage');
    }

    /**
     * Get all applications and yield one application at a time using a generator
     *
     * @todo - Sort and filter?
     *
     * @return \Generator
     */
    public function yieldAllApplications()
    {
        foreach ($this->yieldFullResourceCollection('getApplicationsByPage') as $resource) {
            yield $resource;
        }
    }

    /**
     * Get single application by id
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
     * Create an application
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

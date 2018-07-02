<?php

namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationActivation;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * ApplicationActivationsHandler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ApplicationActivationsHandler extends AbstractHttpHandler
{
    /**
     * Get applications by a specific page number
     *
     * @param int $page The page to retrieve
     * @param string $sort
     * @return ResponseWrapper
     */
    public function getApplicationActivationsByPage(Application $application, $page = 1, $sort = [])
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'activations',
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
    public function getAllApplicationActivations($application)
    {
        return $this->getFullResourceCollection('getApplicationActivationsByPage', $application);
    }

    /**
     * Get all applications and yield one plan at a time using a generator
     *
     * @return \Generator
     */
    public function yieldAllApplicationActivations(Application $application)
    {
        foreach ($this->yieldFullResourceCollection('getApplicationActivationsByPage', $application) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get single activation by id
     *
     * @return ResponseWrapper
     */
    public function getSingleApplicationActivation(Application $application, $activationId)
    {
        $path = vsprintf('%s/%s/%s/%s', [
            'applications',
            $application->getId(),
            'activations',
            $activationId,
        ]);

        return $this->httpClientWrapper->request('get', $path);
    }

    /**
     * Create an activation
     *
     * @return ResponseWrapper
     */
    public function createApplicationActivation(Application $application, ApplicationActivation $applicationActivation)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'activations',
        ]);

        return $this->httpClientWrapper->request('post', $path, [], [], $applicationActivation->getJsonPayload());
    }
}

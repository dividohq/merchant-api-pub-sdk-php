<?php

namespace Divido\MerchantSDK\Handlers\ApplicationActivations;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationActivation;
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
     * Get application activations as a collection, either a specific page or all
     *
     * @param ApiRequestOptions $options API Request options
     * @param Application $application
     * @return ResponseWrapper
     */
    public function getApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        if ($options->isPaginated() === false) {
            return $this->getAllApplicationActivations($options, $application);
        }

        return $this->getApplicationActivationsByPage($options, $application);
    }

    /**
     * Yield application activations one at a time, either from a specific page or all
     *
     * @param ApiRequestOptions $options API Request options
     * @param Application $application
     * @return \Generator
     */
    public function yieldApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        if ($options->isPaginated() === false) {
            foreach ($this->yieldAllApplicationActivations($options, $application) as $activation) {
                yield $activation;
            }
            return;
        }

        $responseWrapper = $this->getApplicationActivationsByPage($options, $application);
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
    protected function yieldAllApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        foreach ($this->yieldFullResourceCollection('getApplicationActivationsByPage', $options, $application) as $resource) {
            yield $resource;
        }
    }


    protected function getApplicationActivationsByPage(ApiRequestOptions $options, Application $application)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'activations',
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
    protected function getAllApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        return $this->getFullResourceCollection('getApplicationActivationsByPage', $options, $application);
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

<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Handlers\ApplicationActivations;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationActivation;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Handler
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 */
class Handler extends AbstractHttpHandler
{
    /**
     * Get application activations as a collection, either a specific page or all
     *
     * @param ApiRequestOptions $options
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
     * @param ApiRequestOptions $options
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
     * Get all application activations and yield one plan at a time using a generator
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return \Generator
     */
    public function yieldAllApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        foreach ($this->yieldFullResourceCollection('getApplicationActivationsByPage', $options, $application) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all application activations by page.
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return ResponseWrapper
     */
    public function getApplicationActivationsByPage(ApiRequestOptions $options, Application $application)
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

        $response = $this->wrapper->request(self::GET_METHOD, $path, $query);
        $parsed = $this->parseResponse($response);

        return $parsed;
    }

    /**
     * Get all application activations in a single array
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return ResponseWrapper
     */
    public function getAllApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        return $this->getFullResourceCollection('getApplicationActivationsByPage', $options, $application);
    }

    /**
     * Get single application activation by id
     *
     * @param Application $application
     * @param string $activationId
     * @return ResponseInterface
     */
    public function getSingleApplicationActivation(Application $application, $activationId)
    {
        $path = vsprintf('%s/%s/%s/%s', [
            'applications',
            $application->getId(),
            'activations',
            $activationId,
        ]);

        return $this->wrapper->request(self::GET_METHOD, $path);
    }

    /**
     * Create an application activation.
     *
     * @param Application $application
     * @param ApplicationActivation $applicationActivation
     * @return ResponseInterface
     */
    public function createApplicationActivation(Application $application, ApplicationActivation $applicationActivation)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'activations',
        ]);

        return $this->wrapper->request(self::POST_METHOD, $path, [], [], $applicationActivation->getJsonPayload());
    }
}

<?php

namespace Divido\MerchantSDK\Handlers\ApplicationCancellations;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationCancellation;
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
     * Get application cancellations as a collection, either a specific page or all
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return ResponseWrapper
     */
    public function getApplicationCancellations(ApiRequestOptions $options, Application $application)
    {
        if ($options->isPaginated() === false) {
            return $this->getAllApplicationCancellations($options, $application);
        }

        return $this->getApplicationCancellationsByPage($options, $application);
    }

    /**
     * Yield application cancellations one at a time, either from a specific page or all
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return \Generator
     */
    public function yieldApplicationCancellations(ApiRequestOptions $options, Application $application)
    {
        if ($options->isPaginated() === false) {
            foreach ($this->yieldAllApplicationCancellations($options, $application) as $cancellation) {
                yield $cancellation;
            }
            return;
        }

        $responseWrapper = $this->getApplicationCancellationsByPage($options, $application);
        foreach ($responseWrapper->getResources() as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all applications and yield one at a time using a generator
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return \Generator
     */
    public function yieldAllApplicationCancellations(ApiRequestOptions $options, Application $application)
    {
        foreach ($this->yieldFullResourceCollection('getApplicationCancellationsByPage', $options, $application) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all application cancellations by page.
     *
     * @param ApiRequestOptions $options
     * @param Application $application
     * @return ResponseWrapper
     */
    public function getApplicationCancellationsByPage(ApiRequestOptions $options, Application $application)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'cancellations',
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
    public function getAllApplicationCancellations(ApiRequestOptions $options, Application $application)
    {
        return $this->getFullResourceCollection('getApplicationCancellationsByPage', $options, $application);
    }

    /**
     * Get single application cancellation by id
     *
     * @param Application $application
     * @param string $cancellationId
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getSingleApplicationCancellation(Application $application, $cancellationId)
    {
        $path = vsprintf('%s/%s/%s/%s', [
            'applications',
            $application->getId(),
            'cancellations',
            $cancellationId,
        ]);

        return $this->httpClientWrapper->request('get', $path);
    }

    /**
     * Create an application cancellation.
     *
     * @param Application $application
     * @param ApplicationCancellation $applicationCancellation
     * @return \GuzzleHttp\Psr7\Response
     */
    public function createApplicationCancellation(Application $application, ApplicationCancellation $applicationCancellation)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'cancellations',
        ]);

        return $this->httpClientWrapper->request('post', $path, [], [], $applicationCancellation->getJsonPayload());
    }
}

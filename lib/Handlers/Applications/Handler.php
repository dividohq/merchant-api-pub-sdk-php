<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Handlers\Applications;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
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
     * Get applications as a collection, either a specific page or all
     *
     * @param ApiRequestOptions $options
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
     * Yield applications one at a time, either from a specific page or all
     *
     * @param ApiRequestOptions $options
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
     * Get all applications and yield one at a time using a generator
     *
     * @param ApiRequestOptions $options
     * @return \Generator
     */
    public function yieldAllApplications(ApiRequestOptions $options)
    {
        foreach ($this->yieldFullResourceCollection('getApplicationsByPage', $options) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get all applications by page.
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getApplicationsByPage(ApiRequestOptions $options)
    {
        $path = vsprintf('%s', [
            'applications',
        ]);

        $query = [
            'page' => $options->getPage(),
            'sort' => $options->getSort(),
            'filter' => $options->getFilters(),
        ];

        $response = $this->wrapper->request(self::GET_METHOD, $path, $query);
        $parsed = $this->parseResponse($response);

        return $parsed;
    }

    /**
     * Get all applications in a single array
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getAllApplications(ApiRequestOptions $options)
    {
        return $this->getFullResourceCollection('getApplicationsByPage', $options);
    }

    /**
     * Get single application by id
     *
     * @param string $applicationId
     * @return ResponseInterface
     */
    public function getSingleApplication($applicationId)
    {
        $path = vsprintf('%s/%s', [
            'applications',
            $applicationId,
        ]);

        return $this->wrapper->request(self::GET_METHOD, $path);
    }

    /**
     * Create an application
     *
     * @param Application $application
     * @return ResponseInterface
     */
    public function createApplication(Application $application, array $query = [], array $headers = [])
    {
        $path = vsprintf('%s', [
            'applications',
        ]);

        return $this->wrapper->request(self::POST_METHOD, $path, $query, $headers, $application->getJsonPayload());
    }

    /**
     * Update an application
     *
     * @param Application $application
     * @return ResponseInterface
     */
    public function updateApplication(Application $application)
    {
        $path = vsprintf('%s/%s', [
            'applications',
            $application->getId(),
        ]);

        return $this->wrapper->request(self::PATCH_METHOD, $path, [], [], $application->getJsonPayload());
    }
}

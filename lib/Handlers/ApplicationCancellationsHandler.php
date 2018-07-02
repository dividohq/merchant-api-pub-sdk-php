<?php

namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationCancellation;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * ApplicationCancellationsHandler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ApplicationCancellationsHandler extends AbstractHttpHandler
{
    /**
     * Get applications by a specific page number
     *
     * @param int $page The page to retrieve
     * @param string $sort
     * @return ResponseWrapper
     */
    public function getApplicationCancellationsByPage(Application $application, $page = 1, $sort = '')
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'cancellations',
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
    public function getAllApplicationCancellations(Application $application, $sort = '')
    {
        return $this->getFullResourceCollection('getApplicationCancellationsByPage', $application, $sort);
        var_dump($return);
        exit;
    }

    /**
     * Get all applications and yield one plan at a time using a generator
     *
     * @return \Generator
     */
    public function yieldAllApplicationCancellations(Application $application)
    {
        foreach ($this->yieldFullResourceCollection('getApplicationCancellationsByPage', $application) as $resource) {
            yield $resource;
        }
    }

    /**
     * Get single cancellation by id
     *
     * @return ResponseWrapper
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
     * Create a cancellation
     *
     * @return ResponseWrapper
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

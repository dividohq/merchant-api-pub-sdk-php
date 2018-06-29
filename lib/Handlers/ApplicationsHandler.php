<?php

namespace Divido\MerchantSDK\Handlers;

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
     * @param array $included A list of inclusions to retrieve
     * @param string $sort
     * @return ResponseWrapper
     */
    public function getApplicationsByPage($page = 1, $included = [], $sort = '')
    {
        $query = [
            'page' => $page,
            'include' => implode(',', $included),
            'sort' => $sort,
        ];

        $response = $this->httpClientWrapper->request('get', 'applications', $query);
        $parsed = $this->parseJsonApiResourceResponse($response);

        return $parsed;
    }


    /**
     * Get all applications in a single array
     *
     * @param array $included
     *
     * @return ResponseWrapper
     */
    public function getAllApplications($included = [])
    {
        return $this->getFullResourceCollection('getApplicationsByPage', $included);
    }

    /**
     * Get all applications and yield one plan at a time using a generator
     *
     * @param array $included
     * @return \Generator
     */
    public function yieldAllApplications($included = [])
    {
        foreach ($this->yieldFullResourceCollection('getApplicationsByPage', $included) as $resource) {
            yield $resource;
        }
    }


}
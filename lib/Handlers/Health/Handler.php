<?php

namespace Divido\MerchantSDK\Handlers\Health;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use GuzzleHttp\Exception\RequestException;

class Handler extends AbstractHttpHandler
{
    /**
     * Check health of the service
     * 
     * @param string $url The service base URL to check
     *
     * @return (bool|int)[]
     */
    public function checkHealth($url)
    {
        $healthcheck = [];
        $path = 'health';

        // Make the request, catch exceptions 
        try {
            $response = $this->httpClientWrapper->request('head', $path);
            $status_code = $response->getStatusCode();

            $healthcheck["status_code"] = $status_code;
            $healthcheck["healthy"] = $status_code === 200 ? true : false;
        } catch (RequestException $e) {
            $healthcheck["healthy"] = false;
        } finally {
            return $healthcheck;
        }
    }
}

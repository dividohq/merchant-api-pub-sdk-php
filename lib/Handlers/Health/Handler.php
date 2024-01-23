<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Handlers\Health;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;

class Handler extends AbstractHttpHandler
{
    /**
     * Check health of the service
     *
     * @return (bool|int)[]
     */
    public function checkHealth()
    {
        $healthcheck = [];
        $path = 'health';

        // Make the request, catch exceptions
        try {
            $response = $this->wrapper->request(self::HEAD_METHOD, $path);
            $status_code = $response->getStatusCode();

            $healthcheck["status_code"] = $status_code;
            $healthcheck["healthy"] = $status_code === 200 ? true : false;
        } catch (\Exception $e) {
            $healthcheck["healthy"] = false;
        } finally {
            return $healthcheck;
        }
    }
}

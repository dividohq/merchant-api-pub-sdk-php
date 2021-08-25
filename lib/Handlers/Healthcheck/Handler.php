<?php

namespace Divido\MerchantSDK\Handlers\Healthcheck;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Handler extends AbstractHttpHandler
{
    /**
     * Check health of the service
     * 
     * @param string $url The service base URL to check
     *
     */
    public function checkHealth($url)
    {
        $healthcheck = [];
        $path = 'health';

        // Make the request, catch exceptions 
        // $response = $this->httpClientWrapper->request('head', $path);
        try {
            $response = $this->httpClientWrapper->request('head', $path);
            $status_code = $response->getStatusCode();

            $healthcheck['status_code'] = $status_code;
            $healthcheck['isHealthy'] = $status_code == 200 ? true : false;
        } catch (RequestException $e) {
            // $healthcheck['isHealthy'] = false;
            // $healthcheck['error_msg'] = Psr7\Message::toString($e->getRequest());
            // $status_code = Psr7\Message::toString($e->getRequest()->getStatusCode());

            // if ($status_code) {
            //     return 'HOST FOUND, BUT COULD NOT DETERMINE HEALTH. RECEIVED STATUS CODE ' . $status_code;
            // }
            // if ($e->hasResponse()) {
            //     $health_exception  .= 'HAS RESPONSE!';
            //     // getResponse()->getStatusCode()
            //     $health_exception  .= ' ------- ' . Psr7\Message::toString($e->getResponse()->getStatusCode());
            // }
        } finally {
            return $healthcheck;
        }

        // try {
        //     $response = $this->httpClientWrapper->request('head', $path);
        // } catch (Exception $e) {

        //     $status_code = $response->getStatusCode();
        //     $isHealthy = $status_code === 200;
        //     //throw $th;
        // }


        // return $response;
    }
}

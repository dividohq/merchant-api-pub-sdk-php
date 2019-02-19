<?php

namespace Divido\MerchantSDK\Handlers\PlatformEnvironments;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * Class Handler
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2019, Divido
 * @package Divido\MerchantSDK
 */
class Handler extends AbstractHttpHandler
{
    /**
     * Get environment based on provided api key
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getPlatformEnvironment()
    {
        return $this->httpClientWrapper->request('get', '/environment');
    }
}

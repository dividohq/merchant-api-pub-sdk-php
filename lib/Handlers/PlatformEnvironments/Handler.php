<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Handlers\PlatformEnvironments;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Handler
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2019, Divido
 */
class Handler extends AbstractHttpHandler
{
    /**
     * Get environment based on provided api key
     *
     * @return ResponseInterface
     */
    public function getPlatformEnvironment()
    {
        return $this->wrapper->request('get', '/environment');
    }
}

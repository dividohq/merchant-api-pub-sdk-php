<?php

namespace Divido\MerchantSDK\Handlers\PlatformEnvironments;

/**
 * Trait ClientProxyTrait
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 */
trait ClientProxyTrait
{
    /**
     * @return array
     */
    abstract protected function getHandlers();

    /**
     * @return Handler
     */
    abstract protected function setHandler($key, $value);

    /**
     * Connect to the platform environments handler.
     *
     * @return Handler
     */
    public function platformEnvironments()
    {
        if (!array_key_exists('platform_environments', $this->getHandlers())) {
            $this->setHandler('platform_environments', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['platform_environments'];
    }
}
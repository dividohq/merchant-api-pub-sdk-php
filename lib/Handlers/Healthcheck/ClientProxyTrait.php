<?php

namespace Divido\MerchantSDK\Handlers\Healthcheck;

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
     * Connect to the healthcheck handler.
     *
     * @return Handler
     */
    public function healthcheck()
    {
        if (!array_key_exists('healthcheck', $this->getHandlers())) {
            $this->setHandler('healthcheck', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['healthcheck'];
    }
}

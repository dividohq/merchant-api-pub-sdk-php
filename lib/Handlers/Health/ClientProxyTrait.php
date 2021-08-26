<?php

namespace Divido\MerchantSDK\Handlers\Health;

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
    public function health()
    {
        if (!array_key_exists('health', $this->getHandlers())) {
            $this->setHandler('health', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['health'];
    }
}

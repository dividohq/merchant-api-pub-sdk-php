<?php

declare(strict_types=1);

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
     * Connect to the health handler.
     *
     * @return Handler
     */
    public function health()
    {
        if (!array_key_exists('health', $this->getHandlers())) {
            $this->setHandler('health', new Handler($this->wrapper));
        }

        return $this->getHandlers()['health'];
    }
}

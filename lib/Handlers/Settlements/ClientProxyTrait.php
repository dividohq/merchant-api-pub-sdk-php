<?php

namespace Divido\MerchantSDK\Handlers\Settlements;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;

/**
 * ClientProxyTrait Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
trait ClientProxyTrait
{
    /**
     * @return array
     */
    abstract protected function getHandlers();
    abstract protected function setHandler(string $key,$value);

    /**
     * @return Handler
     */
    public function settlements()
    {
        if (!array_key_exists('settlements', $this->getHandlers())) {
            $this->setHandler('settlements', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['settlements'];
    }

    public function getSettlementsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        return $this->settlements()->getSettlements($options);
    }

    public function getAllSettlements(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        return $this->settlements()->getSettlements($options);
    }

    public function yieldAllSettlements(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->settlements()->yieldSettlements($options) as $settlement) {
            yield $settlement;
        }
    }

    public function yieldSettlementsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->settlements()->yieldSettlements($options) as $settlement) {
            yield $settlement;
        }
    }
}

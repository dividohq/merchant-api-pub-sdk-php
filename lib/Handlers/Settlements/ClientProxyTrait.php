<?php

namespace Divido\MerchantSDK\Handlers\Settlements;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * Trait ClientProxyTrait
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

    /**
     * @return Handler
     */
    abstract protected function setHandler($key, $value);

    /**
     * Connect to the settlements handler.
     *
     * @return Handler
     */
    public function settlements()
    {
        if (!array_key_exists('settlements', $this->getHandlers())) {
            $this->setHandler('settlements', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['settlements'];
    }

    /**
     * Get settlements by page.
     *
     * @param ApiRequestOptions $options
     *
     * @return ResponseWrapper
     */
    public function getSettlementsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        return $this->settlements()->getSettlements($options);
    }

    /**
     * Get all settlements.
     *
     * @param ApiRequestOptions $options
     *
     * @return ResponseWrapper
     */
    public function getAllSettlements(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        return $this->settlements()->getSettlements($options);
    }

    /**
     * Yield all settlements.
     *
     * @param ApiRequestOptions $options
     *
     * @return ResponseWrapper
     */
    public function yieldAllSettlements(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->settlements()->yieldSettlements($options) as $settlement) {
            yield $settlement;
        }
    }

    /**
     * Yield settlements by page.
     *
     * @param ApiRequestOptions $options
     *
     * @return ResponseWrapper
     */
    public function yieldSettlementsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->settlements()->yieldSettlements($options) as $settlement) {
            yield $settlement;
        }
    }
}

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
    function getSettlementsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        return $this->settlements()->getSettlements($options);
    }

    function getAllSettlements(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        return $this->settlements()->getSettlements($options);
    }

    function yieldAllSettlements(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->settlements()->yieldSettlements($options) as $settlement) {
            yield $settlement;
        }
    }

    function yieldSettlementsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->settlements()->yieldSettlements($options) as $settlement) {
            yield $settlement;
        }
    }
}

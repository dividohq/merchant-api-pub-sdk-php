<?php

namespace Divido\MerchantSDK\Handlers\Finances;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;

/**
 * ClientProxyTrait Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
trait ClientProxyTrait
{
    function getPlansByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        return $this->finances()->getPlans($options);
    }

    function getAllPlans(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        return $this->finances()->getPlans($options);
    }

    function yieldAllPlans(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->finances()->yieldPlans($options) as $finance) {
            yield $finance;
        }
    }

    function yieldPlansByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->finances()->yieldPlans($options) as $finance) {
            yield $finance;
        }
    }
}

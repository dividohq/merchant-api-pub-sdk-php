<?php

namespace Divido\MerchantSDK\Handlers\Finances;

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
    public function finances()
    {
        if (!array_key_exists('finances', $this->getHandlers())) {
            $this->setHandler('finances', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['finances'];
    }

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

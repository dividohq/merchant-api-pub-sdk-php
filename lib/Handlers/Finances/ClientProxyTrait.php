<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Handlers\Finances;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Response\ResponseWrapper;

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
     * Connect to the finances handler.
     *
     * @return Handler
     */
    public function finances()
    {
        if (!array_key_exists('finances', $this->getHandlers())) {
            $this->setHandler('finances', new Handler($this->wrapper));
        }

        return $this->getHandlers()['finances'];
    }

    /**
     * Get plans by page.
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getPlansByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);

        return $this->finances()->getPlans($options);
    }

    /**
     * Get all plans.
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getAllPlans(ApiRequestOptions $options)
    {
        $options->setPaginated(false);

        return $this->finances()->getPlans($options);
    }

    /**
     * Yield all plans.
     *
     * @param ApiRequestOptions $options
     * @return \Generator
     */
    public function yieldAllPlans(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->finances()->yieldPlans($options) as $finance) {
            yield $finance;
        }
    }

    /**
     * Yield plans by page.
     *
     * @param ApiRequestOptions $options
     * @return \Generator
     */
    public function yieldPlansByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->finances()->yieldPlans($options) as $finance) {
            yield $finance;
        }
    }
}

<?php

namespace Divido\MerchantSDK\Handlers\ApplicationRefunds;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
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
     * Connect to the application refunds handler.
     *
     * @return Handler
     */
    public function applicationRefunds()
    {
        if (!array_key_exists('application_refunds', $this->getHandlers())) {
            $this->setHandler('application_refunds', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['application_refunds'];
    }

    /**
     * Get application refunds by page.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function getApplicationRefundsByPage(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(true);
        return $this->applicationRefunds()->getApplicationRefunds($options, $application);
    }

    /**
     * Get all application refunds.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function getAllApplicationRefunds(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(false);
        return $this->applicationRefunds()->getApplicationRefunds($options, $application);
    }

    /**
     * Yield all application refunds.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function yieldAllApplicationRefunds(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(false);
        foreach ($this->applicationRefunds()->yieldApplicationRefunds($options, $application) as $refund) {
            yield $refund;
        }
    }

    /**
     * Yield application refunds by page.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function yieldApplicationRefundsByPage(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(true);
        foreach ($this->applicationRefunds()->yieldApplicationRefunds($options, $application) as $refund) {
            yield $refund;
        }
    }
}

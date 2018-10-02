<?php

namespace Divido\MerchantSDK\Handlers\Applications;

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
     * Connect to the applications handler.
     *
     * @return Handler
     */
    public function applications()
    {
        if (!array_key_exists('applications', $this->getHandlers())) {
            $this->setHandler('applications', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['applications'];
    }

    /**
     * Get applications by page.
     *
     * @param ApiRequestOptions $options
     *
     * @return ResponseWrapper
     */
    public function getApplicationsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        return $this->applications()->getApplications($options);
    }

    /**
     * Get all applications.
     *
     * @param ApiRequestOptions $options
     *
     * @return ResponseWrapper
     */
    public function getAllApplications(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        return $this->applications()->getApplications($options);
    }

    /**
     * Yield all applications.
     *
     * @param ApiRequestOptions $options
     *
     * @return ResponseWrapper
     */
    public function yieldAllApplications(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->applications()->yieldApplications($options) as $application) {
            yield $application;
        }
    }

    /**
     * Yield applications by page.
     *
     * @param ApiRequestOptions $options
     *
     * @return ResponseWrapper
     */
    public function yieldApplicationsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->applications()->yieldApplications($options) as $application) {
            yield $application;
        }
    }
}

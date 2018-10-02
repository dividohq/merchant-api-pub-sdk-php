<?php

namespace Divido\MerchantSDK\Handlers\ApplicationCancellations;

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
     * Connect to the application cancellations handler.
     *
     * @return Handler
     */
    public function applicationCancellations()
    {
        if (!array_key_exists('application_cancellations', $this->getHandlers())) {
            $this->setHandler('application_cancellations', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['application_cancellations'];
    }

    /**
     * Get application cancellations by page.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function getApplicationCancellationsByPage(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(true);
        return $this->applicationCancellations()->getApplicationCancellations($options, $application);
    }

    /**
     * Get all application cancellations.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function getAllApplicationCancellations(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(false);
        return $this->applicationCancellations()->getApplicationCancellations($options, $application);
    }

    /**
     * Yield all application cancellations.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function yieldAllApplicationCancellations(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(false);
        foreach ($this->applicationCancellations()->yieldApplicationCancellations($options, $application) as $cancellation) {
            yield $cancellation;
        }
    }

    /**
     * Yield application cancellations by page.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function yieldApplicationCancellationsByPage(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(true);
        foreach ($this->applicationCancellations()->yieldApplicationCancellations($options, $application) as $cancellation) {
            yield $cancellation;
        }
    }
}

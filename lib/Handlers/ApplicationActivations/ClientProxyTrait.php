<?php

namespace Divido\MerchantSDK\Handlers\ApplicationActivations;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;

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
    abstract protected function setHandler(string $key, $value);

    /**
     * @return Handler
     */
    public function application_activations()
    {
        if (!array_key_exists('application_activations', $this->getHandlers())) {
            $this->setHandler('application_activations', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['application_activations'];
    }

    /**
     * Get application activations by page.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function getApplicationActivationsByPage(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(true);
        return $this->application_activations()->getApplicationActivations($options, $application);
    }

    /**
     * Get all application activations.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function getAllApplicationActivations(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(false);
        return $this->application_activations()->getApplicationActivations($options, $application);
    }

    /**
     * Yield all application activations.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function yieldAllApplicationActivations(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(false);
        foreach ($this->application_activations()->yieldApplicationActivations($options, $application) as $activation) {
            yield $activation;
        }
    }

    /**
     * Yield application activations by page.
     *
     * @param ApiRequestOptions $options
     * @param mixed $application
     *
     * @return ResponseWrapper
     */
    public function yieldApplicationActivationsByPage(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(true);
        foreach ($this->application_activations()->yieldApplicationActivations($options, $application) as $activation) {
            yield $activation;
        }
    }
}

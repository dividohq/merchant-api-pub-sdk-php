<?php

namespace Divido\MerchantSDK\Handlers\ApplicationActivations;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;

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
    public function application_activations()
    {
        if (!array_key_exists('application_activations', $this->getHandlers())) {
            $this->setHandler('application_activations', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['application_activations'];
    }

    public function getApplicationActivationsByPage(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(true);
        return $this->application_activations()->getApplicationActivations($options, $application);
    }

    public function getAllApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        return $this->application_activations()->getApplicationActivations($options, $application);
    }

    public function yieldAllApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        foreach ($this->application_activations()->yieldApplicationActivations($options, $application) as $activation) {
            yield $activation;
        }
    }

    public function yieldApplicationActivationsByPage(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(true);
        foreach ($this->application_activations()->yieldApplicationActivations($options, $application) as $activation) {
            yield $activation;
        }
    }
}

<?php

namespace Divido\MerchantSDK\Handlers\ApplicationActivations;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;

/**
 * ClientProxyTrait Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
trait ClientProxyTrait
{
    function getApplicationActivationsByPage(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(true);
        return $this->application_activations()->getApplicationActivations($options, $application);
    }

    function getAllApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        return $this->application_activations()->getApplicationActivations($options, $application);
    }

    function yieldAllApplicationActivations(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        foreach ($this->application_activations()->yieldApplicationActivations($options, $application) as $activation) {
            yield $activation;
        }
    }

    function yieldApplicationActivationsByPage(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(true);
        foreach ($this->application_activations()->yieldApplicationActivations($options, $application) as $activation) {
            yield $activation;
        }
    }
}

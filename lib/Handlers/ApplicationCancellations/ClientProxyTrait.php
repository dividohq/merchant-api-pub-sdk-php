<?php

namespace Divido\MerchantSDK\Handlers\ApplicationCancellations;

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
    function getApplicationCancellationsByPage(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(true);
        return $this->application_cancellations()->getApplicationCancellations($options, $application);
    }

    function getAllApplicationCancellations(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        return $this->application_cancellations()->getApplicationCancellations($options, $application);
    }

    function yieldAllApplicationCancellations(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        foreach ($this->application_cancellations()->yieldApplicationCancellations($options, $application) as $cancellation) {
            yield $cancellation;
        }
    }

    function yieldApplicationCancellationsByPage(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(true);
        foreach ($this->application_cancellations()->yieldApplicationCancellations($options, $application) as $cancellation) {
            yield $cancellation;
        }
    }
}

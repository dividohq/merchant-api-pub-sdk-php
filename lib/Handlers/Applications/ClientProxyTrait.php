<?php

namespace Divido\MerchantSDK\Handlers\Applications;

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
    function getApplicationsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        return $this->applications()->getApplications($options);
    }

    function getAllApplications(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        return $this->applications()->getApplications($options);
    }

    function yieldAllApplications(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->applications()->yieldApplications($options) as $application) {
            yield $application;
        }
    }

    function yieldApplicationsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->applications()->yieldApplications($options) as $application) {
            yield $application;
        }
    }
}

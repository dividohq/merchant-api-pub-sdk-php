<?php

namespace Divido\MerchantSDK\Handlers\ApplicationRefunds;

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
    function getApplicationRefundsByPage(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(true);
        return $this->application_refunds()->getApplicationRefunds($options, $application);
    }

    function getAllApplicationRefunds(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        return $this->application_refunds()->getApplicationRefunds($options, $application);
    }

    function yieldAllApplicationRefunds(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        foreach ($this->application_refunds()->yieldApplicationRefunds($options, $application) as $refund) {
            yield $refund;
        }
    }

    function yieldApplicationRefundsByPage(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(true);
        foreach ($this->application_refunds()->yieldApplicationRefunds($options, $application) as $refund) {
            yield $refund;
        }
    }
}

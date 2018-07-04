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
    /**
     * @return array
     */
    abstract protected function getHandlers();
    abstract protected function setHandler(string $key,$value);

    /**
     * @return Handler
     */
    public function application_cancellations()
    {
        if (!array_key_exists('application_cancellations', $this->getHandlers())) {
            $this->setHandler('application_cancellations', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['application_cancellations'];
    }

    public function getApplicationCancellationsByPage(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(true);
        return $this->application_cancellations()->getApplicationCancellations($options, $application);
    }

    public function getAllApplicationCancellations(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        return $this->application_cancellations()->getApplicationCancellations($options, $application);
    }

    public function yieldAllApplicationCancellations(ApiRequestOptions $options, Application $application)
    {
        $options->setPaginated(false);
        foreach ($this->application_cancellations()->yieldApplicationCancellations($options, $application) as $cancellation) {
            yield $cancellation;
        }
    }

    public function yieldApplicationCancellationsByPage(ApiRequestOptions $options, $application)
    {
        if (is_string($application)) {
            $application = (new Application)->withId($application);
        }

        $options->setPaginated(true);
        foreach ($this->application_cancellations()->yieldApplicationCancellations($options, $application) as $cancellation) {
            yield $cancellation;
        }
    }
}

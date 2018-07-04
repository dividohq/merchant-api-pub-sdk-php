<?php

namespace Divido\MerchantSDK\Handlers\Applications;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;

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
    public function applications()
    {
        if (!array_key_exists('applications', $this->getHandlers())) {
            $this->setHandler('applications', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['applications'];
    }
    public function getApplicationsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        return $this->applications()->getApplications($options);
    }

    public function getAllApplications(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        return $this->applications()->getApplications($options);
    }

    public function yieldAllApplications(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->applications()->yieldApplications($options) as $application) {
            yield $application;
        }
    }

    public function yieldApplicationsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->applications()->yieldApplications($options) as $application) {
            yield $application;
        }
    }
}

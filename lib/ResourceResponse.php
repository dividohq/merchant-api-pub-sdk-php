<?php

namespace Divido\MerchantSDK;

/**
 * Class ResourceResponse
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ResourceResponse
{
    /**
     * ResourceResponse constructor.
     *
     * @param string $apiKey
     * @param string $environment
     * @param mixed $httpClient
     */
    final public function __construct(string $apiKey, $environment = Environment::SANDBOX, $httpClient = null)
    {
        $this->environment = $environment;

        if ($httpClient === null) {
            $httpClient = new GuzzleAdapter(new \GuzzleHttp\Client());
        }

        $this->httpClientWrapper = new HttpClientWrapper(
            $httpClient,
            Environment::CONFIGURATION[$environment]['base_uri'],
            $apiKey
        );
    }

    /**
     * Get the API environment in use
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Get the Finances method handler
     *
     * @return FinancesHandler
     * @throws \Exception
     */
    public function finances()
    {
        if ($this->financesHandler === null) {
            $this->financesHandler = new FinancesHandler($this->httpClientWrapper);
        }

        return $this->financesHandler;
    }
}

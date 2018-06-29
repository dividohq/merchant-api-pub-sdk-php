<?php

namespace Divido\MerchantSDK;
use Divido\MerchantSDK\Handlers\ApplicationsHandler;
use Divido\MerchantSDK\Handlers\FinancesHandler;
use Divido\MerchantSDK\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;

/**
 * Class Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class Client
{

    /**
     * The API environment to consume
     *
     * @var string
     */
    private $environment;

    /**
     * The endpoint handlers
     *
     * @var array
     */
    private $handlers = [];

    /**
     * HTTP transport wrapper
     *
     * @var HttpClientWrapper
     */
    private $httpClientWrapper;


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
        if (!array_key_exists('finances', $this->handlers)) {
            $this->handlers['finances'] = new FinancesHandler($this->httpClientWrapper);
        }

        return $this->handlers['finances'];
    }

    /**
     * Get the Applications methods handler
     *
     * @return ApplicationsHandler
     * @throws \Exception
     */
    public function applications()
    {
        if (!array_key_exists('applications', $this->handlers)) {
            $this->handlers['applications'] = new ApplicationsHandler($this->httpClientWrapper);
        }

        return $this->handlers['applications'];
    }




}
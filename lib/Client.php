<?php

namespace Divido\MerchantSDK;

use Divido\MerchantSDK\Handlers\Applications\ClientProxyTrait as ApplicationsClientProxyTrait;
use Divido\MerchantSDK\Handlers\Applications\Handler as ApplicationsHandler;
use Divido\MerchantSDK\Handlers\ApplicationActivations\ClientProxyTrait as ApplicationActivationsClientProxyTrait;
use Divido\MerchantSDK\Handlers\ApplicationActivations\Handler as ApplicationActivationsHandler;
use Divido\MerchantSDK\Handlers\ApplicationCancellations\ClientProxyTrait as ApplicationCancellationsClientProxyTrait;
use Divido\MerchantSDK\Handlers\ApplicationCancellations\Handler as ApplicationCancellationsHandler;
use Divido\MerchantSDK\Handlers\ApplicationDocuments\Handler as ApplicationDocumentsHandler;
use Divido\MerchantSDK\Handlers\ApplicationRefunds\ClientProxyTrait as ApplicationRefundsClientProxyTrait;
use Divido\MerchantSDK\Handlers\ApplicationRefunds\Handler as ApplicationRefundsHandler;
use Divido\MerchantSDK\Handlers\Channels\ClientProxyTrait as ChannelsClientProxyTrait;
use Divido\MerchantSDK\Handlers\Channels\Handler as ChannelsHandler;
use Divido\MerchantSDK\Handlers\Finances\ClientProxyTrait as FinancesClientProxyTrait;
use Divido\MerchantSDK\Handlers\Finances\Handler as FinancesHandler;
use Divido\MerchantSDK\Handlers\Settlements\ClientProxyTrait as SettlementsClientProxyTrait;
use Divido\MerchantSDK\Handlers\Settlements\Handler as SettlementsHandler;
use Divido\MerchantSDK\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;

/**
 * Class Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class Client
{
    use ApplicationsClientProxyTrait, ApplicationActivationsClientProxyTrait, ApplicationCancellationsClientProxyTrait,
        ApplicationRefundsClientProxyTrait, ChannelsClientProxyTrait, FinancesClientProxyTrait,
        SettlementsClientProxyTrait;

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

    /**
     * Get the Settlements methods handler
     *
     * @return SettlementsHandler
     * @throws \Exception
     */
    public function settlements()
    {
        if (!array_key_exists('settlements', $this->handlers)) {
            $this->handlers['settlements'] = new SettlementsHandler($this->httpClientWrapper);
        }

        return $this->handlers['settlements'];
    }

    /**
     * Get the Channels methods handler
     *
     * @return ChannelsHandler
     * @throws \Exception
     */
    public function channels()
    {
        if (!array_key_exists('channels', $this->handlers)) {
            $this->handlers['channels'] = new ChannelsHandler($this->httpClientWrapper);
        }

        return $this->handlers['channels'];
    }

    /**
     * Get the Activations methods handler
     *
     * @return ApplicationActivationsHandler
     * @throws \Exception
     */
    public function application_activations()
    {
        if (!array_key_exists('application_activations', $this->handlers)) {
            $this->handlers['application_activations'] = new ApplicationActivationsHandler($this->httpClientWrapper);
        }

        return $this->handlers['application_activations'];
    }

    /**
     * Get the Cancellations methods handler
     *
     * @return ApplicationCancellationsHandler
     * @throws \Exception
     */
    public function application_cancellations()
    {
        if (!array_key_exists('application_cancellations', $this->handlers)) {
            $this->handlers['application_cancellations'] = new ApplicationCancellationsHandler($this->httpClientWrapper);
        }

        return $this->handlers['application_cancellations'];
    }

    /**
     * Get the Refunds methods handler
     *
     * @return ApplicationRefundsHandler
     * @throws \Exception
     */
    public function application_refunds()
    {
        if (!array_key_exists('application_refunds', $this->handlers)) {
            $this->handlers['application_refunds'] = new ApplicationRefundsHandler($this->httpClientWrapper);
        }

        return $this->handlers['application_refunds'];
    }

    /**
     * Get the Documents methods handler
     *
     * @return ApplicationDocumentsHandler
     * @throws \Exception
     */
    public function application_documents()
    {
        if (!array_key_exists('application_documents', $this->handlers)) {
            $this->handlers['application_documents'] = new ApplicationDocumentsHandler($this->httpClientWrapper);
        }

        return $this->handlers['application_documents'];
    }


}

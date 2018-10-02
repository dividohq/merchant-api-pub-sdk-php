<?php

namespace Divido\MerchantSDK;

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
    use Handlers\Applications\ClientProxyTrait;
    use Handlers\ApplicationActivations\ClientProxyTrait;
    use Handlers\ApplicationCancellations\ClientProxyTrait;
    use Handlers\ApplicationRefunds\ClientProxyTrait;
    use Handlers\Channels\ClientProxyTrait;
    use Handlers\Finances\ClientProxyTrait;
    use Handlers\Settlements\ClientProxyTrait;

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

    /**
     * Client constructor.
     *
     * @param string $apiKey
     * @param string $environment
     * @param mixed $httpClient
     */
    final public function __construct($apiKey, $environment = Environment::SANDBOX, $httpClient = null)
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
     * Get all the handlers as an array.
     *
     * @return array
     */
    protected function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * Set a defined handler.
     *
     * @return array
     */
    protected function setHandler($key, $value)
    {
        $this->handlers[$key] = $value;
    }
}

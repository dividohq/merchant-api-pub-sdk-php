<?php

namespace Divido\MerchantSDK;

use Divido\MerchantSDK\Handlers\ApplicationDocuments\Handler as ApplicationDocumentsHandler;
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


    /**
     * @return array
     */
    protected function getHandlers()
    {
        return $this->handlers;
    }

    protected function setHandler(string $key, $value)
    {
        $this->handlers[$key] = $value;
    }
}

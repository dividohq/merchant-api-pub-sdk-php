<?php

namespace Divido\MerchantSDK\Wrappers;

use Divido\DocumentApiSdk\Exceptions\DocumentApiSdkException;
use Divido\MerchantSDK\Exceptions\MerchantApiBadResponseException;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\RequestFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class HttpWrapper implements WrapperInterface
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * The Divido Merchant API key
     *
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @param string $baseUrl
     * @param string $apiKey
     * @param ClientInterface $httpClient
     * @param RequestFactory $requestFactory
     */
    public function __construct(
        string $baseUrl,
        string $apiKey,
        ClientInterface $httpClient = null,
        RequestFactory $requestFactory = null
    ) {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? MessageFactoryDiscovery::find();
    }

    /**
     * Request method
     *
     * @param string $method
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @param string $body
     * @return ResponseInterface
     * @throws DocumentApiSdkException
     */
    public function request(string $method, string $uri, array $query = [], array $headers = [], $body = null): ResponseInterface
    {
        // Add the header to each call
        $headers['X-Divido-Api-Key'] = $this->apiKey;

        $uri = $this->baseUrl  . '/' . $uri . '?' . http_build_query($query, '', '&');

        $request = $this->requestFactory->createRequest($method, $uri, $headers, $body);

        $response = $this->httpClient->sendRequest($request);
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400 && $statusCode < 500) {
            $response = json_decode($response->getBody()->getContents());

            throw new MerchantApiBadResponseException($response->message, $response->code, $response->context ?? null);
        } else if ($statusCode >= 500) {
            $response = json_decode($response->getBody()->getContents());

            throw new MerchantApiBadResponseException($response->message, $response->code, $response->context ?? null);
        }

        return $response;
    }
}
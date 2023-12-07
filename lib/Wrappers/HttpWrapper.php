<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Wrappers;

use Divido\MerchantSDK\Exceptions\InvalidRequestException;
use Divido\MerchantSDK\Exceptions\MerchantApiBadResponseException;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

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
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @param string $baseUrl
     * @param string $apiKey
     * @param ClientInterface $httpClient
     * @param RequestFactoryInterface $requestFactory
     * @param StreamFactoryInterface $streamFactory
     */
    public function __construct(
        string $baseUrl,
        string $apiKey,
        ClientInterface $httpClient = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient ?: Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory(); 
    }

    /**
     * Request method
     *
     * @param string $method
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @param mixed $body
     * @return ResponseInterface
     * @throws MerchantApiBadResponseException
     */
    public function request(string $method, string $uri, array $query = [], array $headers = [], $body = null)
    {
        // Add the header to each call
        $headers['X-Divido-Api-Key'] = $this->apiKey;

        $uri = $this->baseUrl  . '/' . $uri . '?' . http_build_query($query, '', '&');

        $request = $this->requestFactory->createRequest(
            strtoupper($method), 
            $uri
        );

        if(is_string($body) == 'string') {
            $request = $request->withBody($this->streamFactory->createStream($body));
        } elseif (class_implements(\Psr\Http\Message\StreamInterface::class)){
            $request = $request->withBody($body);
        } elseif ($body !== null){
            throw new InvalidRequestException("Unexpected request body received");
        }
        
        foreach($headers as $key=>$value){
            $request = $request->withHeader($key, $value);
        }

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new MerchantApiBadResponseException($e->getMessage(), $e->getCode(), null);
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400 && $statusCode <= 599) {
            $response = json_decode($response->getBody()->getContents());

            throw new MerchantApiBadResponseException($response->message, $response->code, $response->context ?: null);
        }

        return $response;
    }
}

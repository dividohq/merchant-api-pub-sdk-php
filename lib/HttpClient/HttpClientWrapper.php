<?php


namespace Divido\MerchantSDK\HttpClient;

use GuzzleHttp\Psr7\Uri;

/**
 * Class HttpClientWrapper
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class HttpClientWrapper
{
    /**
     * @var IHttpClient
     */
    private $httpClient;

    /**
     * The Divido Merchant API key
     *
     * @var string
     */
    private $apiKey;

    /**
     * The base URL for requests
     *
     * @var string
     */
    private $baseUrl;

    /**
     * HttpClientWrapper constructor.
     *
     * @param IHttpClient $httpClient
     * @param string $baseUrl
     * @param string $apiKey
     */
    final public function __construct(IHttpClient $httpClient, $baseUrl, $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * Send an HTTP request to the Merchant API
     *
     * Adds the base URL and Merchant API Key to the request before sending.
     *
     * @param string $method The HTTP method to use for this request
     * @param string $path The path of the URL
     * @param array $query Any query string key/value pairs to add to the request
     * @param array $headers Any header key/value pairs to add to the request
     * @param string $payload The payload to send (if HTTP method supports it)
     *
     * @return \Psr\Http\Message\ResponseInterface The HTTP response
     */
    public function request($method, $path, array $query = [], array $headers = [], $payload = '')
    {
        // Add the header to each call
        $headers['X-Divido-Api-Key'] = $this->apiKey;

        $path = substr($path, 0, 1) === '/'
            ? $path
            : '/' . $path;

        // Create URI
        $uri = new Uri($this->baseUrl . $path . (empty($query) ? '' : '?' . http_build_query($query, '', '&')));

        // Send request
        switch($method) {
            case 'get':
                return $this->httpClient->get($uri, $headers);
                break;
            case 'post':
                return $this->httpClient->post($uri, $headers, $payload);
                break;
            case 'patch':
                return $this->httpClient->patch($uri, $headers, $payload);
                break;
            case 'delete':
                return $this->httpClient->delete($uri, $headers);
                break;
            default:
                throw new \InvalidArgumentException('Divido Merchant SDK does not support this HTTP method');
                break;
        }
    }
}

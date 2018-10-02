<?php

namespace Divido\MerchantSDK\HttpClient;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class GuzzleAdapter
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class GuzzleAdapter implements IHttpClient
{
    /**
     * The Guzzle client (might be mocked, so accept interface)
     *
     * @var ClientInterface
     */
    private $client;

    ########################################################
    #                                                      #
    #             P U B L I C    M E T H O D S             #
    #                                                      #
    ########################################################

    /**
     * Guzzle Adapter implementation for HTTP client interface
     *
     * @param ClientInterface $client The Guzzle client (might be mocked, so accept interface)
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Submit an HTTP GET request
     *
     * @param UriInterface $url The url to send the request to $uri
     * @param array $headers A key/value pair array of headers to send with the request
     *
     * @return ResponseInterface The HTTP response (PSR implementation)
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(UriInterface $url, array $headers = [])
    {
        return $this->getClient()->send(
            new Request('GET', $url, $headers), [
                'http_errors' => false,
            ]
        );
    }

    /**
     * Submit an HTTP POST request
     *
     * @param UriInterface $url The url to send the request to $uri
     * @param array $headers A key/value pair array of headers to send with the request
     * @param string $payload The payload to send with the request
     *
     * @return ResponseInterface The HTTP response (PSR implementation)
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(UriInterface $url, array $headers = [], $payload = '')
    {
        return $this->getClient()->send(
            new Request('POST', $url, $headers, $payload), [
                'http_errors' => false,
            ]
        );
    }

    /**
     * Submit an HTTP DELETE request
     *
     * @param UriInterface $url The url to send the request to $uri
     * @param array $headers A key/value pair array of headers to send with the request
     *
     * @return ResponseInterface The HTTP response (PSR implementation)
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(UriInterface $url, array $headers = [])
    {
        return $this->getClient()->send(
            new Request('DELETE', $url, $headers), [
                'http_errors' => false,
            ]
        );
    }

    /**
     * Submit an HTTP PATCH request
     *
     * @param UriInterface $url The url to send the request to $uri
     * @param array $headers A key/value pair array of headers to send with the request
     * @param string $payload The payload to send with the request
     *
     * @return ResponseInterface The HTTP response (PSR implementation)
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function patch(UriInterface $url, array $headers = [], $payload = '')
    {
        return $this->getClient()->send(
            new Request('PATCH', $url, $headers, $payload), [
                'http_errors' => false,
            ]
        );
    }

    ########################################################
    #                                                      #
    #          P R O T E C T E D    M E T H O D S          #
    #                                                      #
    ########################################################

    // No protected methods

    ########################################################
    #                                                      #
    #            P R I V A T E    M E T H O D S            #
    #                                                      #
    ########################################################

    /**
     * Get the current Guzzle Client
     *
     * @return ClientInterface
     */
    private function getClient()
    {
        return $this->client;
    }
}

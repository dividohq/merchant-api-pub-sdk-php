<?php

namespace Divido\MerchantSDK\HttpClient;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

interface IHttpClient
{
    /**
     * Submit an HTTP GET request
     *
     * @param UriInterface $url The url to send the request to $uri
     * @param array $headers A key/value pair array of headers to send with the request
     *
     * @return ResponseInterface The HTTP response (PSR implementation)
     */
    public function get(UriInterface $url, array $headers = []);

    /**
     * Submit an HTTP POST request
     *
     * @param UriInterface $url The url to send the request to $uri
     * @param array $headers A key/value pair array of headers to send with the request
     * @param string $payload The payload to send with the request
     *
     * @return ResponseInterface The HTTP response (PSR implementation)
     */
    public function post(UriInterface $url, array $headers = [], $payload = '');

    /**
     * Submit an HTTP DELETE request
     *
     * @param UriInterface $url The url to send the request to $uri
     * @param array $headers A key/value pair array of headers to send with the request
     *
     * @return ResponseInterface The HTTP response (PSR implementation)
     */
    public function delete(UriInterface $url, array $headers = []);

    /**
     * Submit an HTTP PATCH request
     *
     * @param UriInterface $url The url to send the request to $uri
     * @param array $headers A key/value pair array of headers to send with the request
     * @param string $payload The payload to send with the request
     *
     * @return ResponseInterface The HTTP response (PSR implementation)
     */
    public function patch(UriInterface $url, array $headers = [], $payload = '');
}

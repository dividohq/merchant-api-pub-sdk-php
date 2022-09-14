<?php

namespace Divido\MerchantSDK\Wrappers;

use Divido\MerchantSDK\Exceptions\MerchantApiBadResponseException;
use Psr\Http\Message\ResponseInterface;

interface WrapperInterface
{
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
    public function request(string $method, string $uri, array $query = [], array $headers = [], $body = null);
}

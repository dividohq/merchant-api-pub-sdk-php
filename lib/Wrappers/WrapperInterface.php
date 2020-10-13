<?php

namespace Divido\MerchantSDK\Wrappers;

interface WrapperInterface
{
    /**
     * @param string $method
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @param null|string $body
     * @return mixed
     */
    public function request(string $method, string $uri, array $query = [], array $headers = [], $body = null);
}

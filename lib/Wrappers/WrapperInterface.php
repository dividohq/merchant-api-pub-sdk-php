<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Wrappers;

use Divido\MerchantSDK\Exceptions\MerchantApiBadResponseException;
use Psr\Http\Message\ResponseInterface;

interface WrapperInterface
{
    const NO_BODY = '';

    /**
     * Request method
     *
     * @param string $method
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @param string $body
     * @return ResponseInterface
     * @throws MerchantApiBadResponseException
     */
    public function request(string $method, string $uri, array $query = [], array $headers = [], string $body = self::NO_BODY);
}

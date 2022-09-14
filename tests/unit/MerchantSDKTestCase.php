<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Http\Message\RequestFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class MerchantSDKTestCase extends TestCase
{
    protected function createResponseMock($status = 200, $headers = [], $body = null)
    {
        $stream = self::createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($body);

        $response = self::createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($status);
        $response->method('getBody')->willReturn($stream);

        return $response;
    }

    protected function createRequestFactory()
    {
        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        return $requestFactory;
    }
}

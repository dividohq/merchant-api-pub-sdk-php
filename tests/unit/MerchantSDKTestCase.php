<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
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
        $requestFactory = self::createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')->willReturn($this->createMockRequest());

        return $requestFactory;
    }

    protected function createMockRequest()
    {
        $mockRequest = self::createMock(RequestInterface::class);
        $mockRequest->method('withHeader')->willReturnSelf();
        $mockRequest->method('withBody')->willReturnSelf();

        return $mockRequest;
    }
}

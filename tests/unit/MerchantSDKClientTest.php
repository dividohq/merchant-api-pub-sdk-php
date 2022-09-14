<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Http\Message\RequestFactory;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class MerchantSDKClientTest extends TestCase
{
    public function test_InstantiationWithoutEnvironment_UsesSandboxEnvironment()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper);
        $this->assertSame(Environment::SANDBOX, $sdk->getEnvironment());
    }

    public function test_InstantiationWithEnvironment_UsesPassedEnvironment()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::PRODUCTION);
        $this->assertSame(Environment::PRODUCTION, $sdk->getEnvironment());
    }
}

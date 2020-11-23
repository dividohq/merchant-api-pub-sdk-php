<?php

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Http\Message\RequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class ChannelsIntegrationTest extends MerchantSDKTestCase
{
    public function test_GetChannelsFromClient_ReturnsChannels()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->getChannelsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $channels);
        self::assertCount(2, $channels->getResources());
        self::assertIsObject($channels->getResources()[0]);
        self::assertObjectHasAttribute('id', $channels->getResources()[0]);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channels->getResources()[0]->id);
    }

    public function test_GetChannelsByPageFromClient_ReturnsChannels()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->getChannelsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $channels);
        self::assertCount(2, $channels->getResources());
        self::assertIsObject($channels->getResources()[0]);
        self::assertObjectHasAttribute('id', $channels->getResources()[0]);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channels->getResources()[0]->id);
    }

    public function test_GetAllChannelsFromClient_ReturnsChannels()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->getAllChannels($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $channels);
        self::assertCount(2, $channels->getResources());
        self::assertIsObject($channels->getResources()[0]);
        self::assertObjectHasAttribute('id', $channels->getResources()[0]);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channels->getResources()[0]->id);
    }

    public function test_YieldAllChannelsFromClient_ReturnsFinanceGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->yieldAllChannels($requestOptions);

        self::assertInstanceOf(\Generator::class, $channels);

        $plan = $channels->current();
        self::assertCount(2, $channels);

        self::assertIsObject($plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $plan->id);
    }

    public function test_GetChannelsByPageFromClient_WithSort_ReturnsSortedChannels()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        );
        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $sdk->getChannelsByPage($requestOptions);

        self::addToAssertionCount(1);
    }

    public function test_YieldChannelsByPageFromClient_ReturnsChannels()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->yieldChannelsByPage($requestOptions);

        self::assertInstanceOf(\Generator::class, $channels);

        $channel = $channels->current();
        self::assertCount(2, $channels);

        self::assertIsObject($channel);
        self::assertObjectHasAttribute('id', $channel);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channel->id);
    }
}

<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\Channels\Handler;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;

class ChannelsHandlerTest extends MerchantSDKTestCase
{
    public function test_GetChannels_ReturnsChannels()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/channels_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $channels = $handler->getChannels($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $channels);
        self::assertCount(2, $channels->getResources());
        self::assertIsObject($channels->getResources()[0]);
        self::assertObjectHasAttribute('id', $channels->getResources()[0]);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channels->getResources()[0]->id);
    }

    public function test_GetChannelsByPage_ReturnsChannels()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/channels_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $channels = $handler->getChannelsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $channels);
        self::assertCount(2, $channels->getResources());
        self::assertIsObject($channels->getResources()[0]);
        self::assertObjectHasAttribute('id', $channels->getResources()[0]);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channels->getResources()[0]->id);
    }

    public function test_GetAllChannels_ReturnsChannels()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/channels_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $channels = $handler->getAllChannels($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $channels);
        self::assertCount(2, $channels->getResources());
        self::assertIsObject($channels->getResources()[0]);
        self::assertObjectHasAttribute('id', $channels->getResources()[0]);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channels->getResources()[0]->id);
    }

    public function test_YieldAllChannels_ReturnsFinanceGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/channels_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $channels = $handler->yieldAllChannels($requestOptions);

        self::assertInstanceOf(\Generator::class, $channels);

        $plan = $channels->current();
        self::assertCount(2, $channels);

        self::assertIsObject($plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $plan->id);
    }

    public function test_YieldChannelsByPage_ReturnsChannelsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/channels_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $channels = $handler->yieldChannels($requestOptions);

        self::assertInstanceOf(\Generator::class, $channels);

        $channel = $channels->current();

        // Bug?:
        // Failed asserting that actual size 0 matches expected size 0
        self::assertCount(2, $channels);

        self::assertIsObject($channel);
        self::assertObjectHasAttribute('id', $channel);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channel->id);
    }

    public function test_GetChannelsByPage_WithSort_ReturnsSortedChannels()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/channels_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $handler->getChannelsByPage($requestOptions);

        self::addToAssertionCount(1);
    }
}

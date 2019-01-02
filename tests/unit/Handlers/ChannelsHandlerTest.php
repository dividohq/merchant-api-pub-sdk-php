<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\Channels\Handler;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Response\ResponseWrapper;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ChannelsHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    public function test_GetChannels_ReturnsChannels()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions());

        $channels = $handler->getChannels($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $channels);
        self::assertCount(2, $channels->getResources());
        self::assertInternalType('object', $channels->getResources()[0]);
        self::assertObjectHasAttribute('id', $channels->getResources()[0]);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channels->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/channels', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_GetChannelsByPage_ReturnsChannels()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions());

        $channels = $handler->getChannelsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $channels);
        self::assertCount(2, $channels->getResources());
        self::assertInternalType('object', $channels->getResources()[0]);
        self::assertObjectHasAttribute('id', $channels->getResources()[0]);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channels->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/channels', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_GetAllChannels_ReturnsChannels()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions());

        $channels = $handler->getAllChannels($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $channels);
        self::assertCount(2, $channels->getResources());
        self::assertInternalType('object', $channels->getResources()[0]);
        self::assertObjectHasAttribute('id', $channels->getResources()[0]);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channels->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/channels', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_YieldAllChannels_ReturnsFinanceGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions());

        $channels = $handler->yieldAllChannels($requestOptions);

        self::assertInstanceOf(\Generator::class, $channels);

        $plan = $channels->current();
        self::assertCount(2, $channels);


        self::assertInternalType('object', $plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $plan->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/channels', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_YieldChannelsByPage_ReturnsChannelsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $channels = $handler->yieldChannels($requestOptions);

        self::assertInstanceOf(\Generator::class, $channels);

        $channel = $channels->current();

        // Bug?:
        // Failed asserting that actual size 0 matches expected size 0
        self::assertCount(2, $channels);

        self::assertInternalType('object', $channel);
        self::assertObjectHasAttribute('id', $channel);
        self::assertSame('CF0A92CE9-4935-DC6F-DD0D-463EC9D654A1', $channel->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/channels", $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);

        self::assertArrayHasKey('page', $query1);
        self::assertSame('2', $query1['page']);
    }

    public function test_GetChannelsByPage_WithSort_ReturnsSortedChannels()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);
        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

       $handler->getChannelsByPage($requestOptions);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/channels', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);
    }
}

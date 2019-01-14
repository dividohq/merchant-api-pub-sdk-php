<?php

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\Channels\Handler;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ChannelsIntegrationTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    public function test_GetChannelsFromClient_ReturnsChannels()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->getChannelsByPage($requestOptions);

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

    public function test_GetChannelsByPageFromClient_ReturnsChannels()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->getChannelsByPage($requestOptions);

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

    public function test_GetAllChannelsFromClient_ReturnsChannels()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->getAllChannels($requestOptions);

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

    public function test_YieldAllChannelsFromClient_ReturnsFinanceGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->yieldAllChannels($requestOptions);

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

    public function test_GetChannelsByPageFromClient_WithSort_ReturnsSortedChannels()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);
        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $sdk->getChannelsByPage($requestOptions);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/channels', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);
    }

    public function test_YieldChannelsByPageFromClient_ReturnsChannels()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/channels_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $channels = $sdk->yieldChannelsByPage($requestOptions);

        self::assertInstanceOf(\Generator::class, $channels);

        $channel = $channels->current();
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
        self::assertSame('1', $query1['page']);
    }
}

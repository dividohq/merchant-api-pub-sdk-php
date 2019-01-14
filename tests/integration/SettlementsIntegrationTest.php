<?php

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Handlers\Settlements\Handler;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Models\Settlement;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class SettlementsIntegrationTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $settlementId = '6EC506EE-7919-11E8-A4CE-0242AC1E000B';

    public function test_GetSettlementsFromClient_ReturnsSettlements()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(1);

        $settlements = $sdk->getSettlementsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $settlements);
        self::assertCount(4, $settlements->getResources());
        self::assertInternalType('object', $settlements->getResources()[0]);
        self::assertObjectHasAttribute('id', $settlements->getResources()[0]);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlements->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/settlements', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_GetSettlementsByPageFromClient_ReturnsSettlements()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(1);

        $settlements = $sdk->getSettlementsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $settlements);
        self::assertCount(4, $settlements->getResources());
        self::assertInternalType('object', $settlements->getResources()[0]);
        self::assertObjectHasAttribute('id', $settlements->getResources()[0]);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlements->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/settlements', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_GetAllSettlementsFromClient_ReturnsSettlements()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $settlements = $sdk->getAllSettlements($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $settlements);
        self::assertCount(4, $settlements->getResources());
        self::assertInternalType('object', $settlements->getResources()[0]);
        self::assertObjectHasAttribute('id', $settlements->getResources()[0]);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlements->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/settlements', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_YieldAllSettlementsFromClient_ReturnsSettlementGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $settlements = $sdk->yieldAllSettlements($requestOptions);

        self::assertInstanceOf(\Generator::class, $settlements);

        $plan = $settlements->current();
        self::assertCount(4, $settlements);


        self::assertInternalType('object', $plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $plan->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/settlements', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_GetSettlementsByPageFromClient_WithSort_ReturnsSortedSettlements()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);
        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(1)->setSort('-created_at');

        $sdk->getSettlementsByPage($requestOptions);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/settlements', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);
    }

    public function test_YieldSettlementsByPageFromClient_ReturnsSettlements()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $settlements = $sdk->yieldSettlementsByPage($requestOptions);

        self::assertInstanceOf(\Generator::class, $settlements);

        $settlement = $settlements->current();
        self::assertCount(4, $settlements);

        self::assertInternalType('object', $settlement);
        self::assertObjectHasAttribute('id', $settlement);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlement->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/settlements", $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);

        self::assertArrayHasKey('page', $query1);
        self::assertSame('1', $query1['page']);
    }
}

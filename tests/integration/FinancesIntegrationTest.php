<?php

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\Finances\Handler;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class FinancesIntegrationTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    public function test_GetFinancesFromClient_ReturnsFinances()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $plans = $sdk->getPlansByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $plans);
        self::assertCount(4, $plans->getResources());
        self::assertInternalType('object', $plans->getResources()[0]);
        self::assertObjectHasAttribute('id', $plans->getResources()[0]);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plans->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/finance-plans', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('3', $query['page']);
    }

    public function test_GetFinancesByPageFromClient_ReturnsFinances()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $plans = $sdk->getPlansByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $plans);
        self::assertCount(4, $plans->getResources());
        self::assertInternalType('object', $plans->getResources()[0]);
        self::assertObjectHasAttribute('id', $plans->getResources()[0]);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plans->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/finance-plans', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('3', $query['page']);
    }

    public function test_GetAllFinancesFromClient_ReturnsFinances()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $plans = $sdk->getAllPlans($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $plans);
        self::assertCount(4, $plans->getResources());
        self::assertInternalType('object', $plans->getResources()[0]);
        self::assertObjectHasAttribute('id', $plans->getResources()[0]);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plans->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/finance-plans', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_YieldAllFinancesFromClient_ReturnsFinanceGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $plans = $sdk->yieldAllPlans($requestOptions);

        self::assertInstanceOf(\Generator::class, $plans);

        $plan = $plans->current();
        self::assertCount(4, $plans);


        self::assertInternalType('object', $plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plan->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/finance-plans', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('1', $query['page']);
    }

    public function test_GetFinancesByPageFromClient_WithSort_ReturnsSortedFinances()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);
        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(1)->setSort('-created_at');

        $sdk->getPlansByPage($requestOptions);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/finance-plans', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);
    }

    public function test_YieldPlansByPageFromClient_ReturnsPlans()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $plans = $sdk->yieldPlansByPage($requestOptions);

        self::assertInstanceOf(\Generator::class, $plans);

        $plan = $plans->current();
        self::assertCount(4, $plans);

        self::assertInternalType('object', $plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plan->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/finance-plans", $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);

        self::assertArrayHasKey('page', $query1);
        self::assertSame('1', $query1['page']);
    }
}

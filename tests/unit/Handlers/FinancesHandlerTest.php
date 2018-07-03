<?php
namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;

use Divido\MerchantSDK\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Response\ResponseWrapper;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class FinancesHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    function test_GetFinancesByPage_ReturnsFinances()
    {

        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

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

    function test_GetAllFinances_ReturnsFinances()
    {

        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

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

    function test_YieldAllFinances_ReturnsFinanceGenerator()
    {

        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

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

    function test_GetFinancesByPage_WithSort_ReturnsSortedFinances()
    {

        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

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

}

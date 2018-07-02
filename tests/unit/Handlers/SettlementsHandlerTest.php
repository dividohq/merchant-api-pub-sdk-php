<?php
namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Models\Settlement;
use Divido\MerchantSDK\Response\ResponseWrapper;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class SettlementsHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $settlementId = '6EC506EE-7919-11E8-A4CE-0242AC1E000B';

    function test_GetSettlementsByPage_ReturnsSettlements()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $settlements = $sdk->settlements()->getSettlementsByPage(1);

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

    function test_GetAllSettlements_ReturnsSettlements()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $settlements = $sdk->settlements()->getAllSettlements();

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

    function test_YieldAllSettlements_ReturnsFinanceGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $settlements = $sdk->settlements()->yieldAllSettlements();

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

    function test_GetSettlementsByPage_WithSort_ReturnsSortedSettlements()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_page_1.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $sdk->settlements()->getSettlementsByPage(1, '-created_at');

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/settlements', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);
    }

    function test_GetSingleSettlement_ReturnsSingleSettlement()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/settlements_get_one.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $settlement = (new Settlement)->withId($this->settlementId);

        $response = $sdk->settlements()->getSingleSettlement($settlement);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/settlements/6EC506EE-7919-11E8-A4CE-0242AC1E000B', $history[0]['request']->getUri()->getPath());

        $result = json_decode($response->getBody(), true);

        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $result['data']['id']);
    }
}

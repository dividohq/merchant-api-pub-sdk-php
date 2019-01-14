<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\ApplicationRefunds\Handler;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ApplicationRefundsHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    public function test_GetApplicationRefunds_ReturnsApplicationsRefunds()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions());

        $application = (new Application)->withId($this->applicationId);

        $refunds = $handler->getApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $refunds);
        self::assertCount(2, $refunds->getResources());

        self::assertInternalType('object', $refunds->getResources()[0]);
        self::assertObjectHasAttribute('id', $refunds->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refunds->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/refunds", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
    }

    public function test_GetApplicationRefundsByPage_ReturnsApplicationsRefunds()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions());

        $application = (new Application)->withId($this->applicationId);

        $refunds = $handler->getApplicationRefundsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $refunds);
        self::assertCount(2, $refunds->getResources());

        self::assertInternalType('object', $refunds->getResources()[0]);
        self::assertObjectHasAttribute('id', $refunds->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refunds->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/refunds", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
    }

    public function test_GetAllApplicationRefunds_ReturnsAllApplicationRefunds()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions());

        $application = (new Application)->withId($this->applicationId);

        $refunds = $handler->getAllApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $refunds);
        // self::assertCount(2, $refunds->getResources());
        self::assertInternalType('object', $refunds->getResources()[0]);
        self::assertObjectHasAttribute('id', $refunds->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refunds->getResources()[0]->id);
        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $refunds->getResources()[1]->id);

        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/refunds", $history[0]['request']->getUri()->getPath());
        self::assertSame("/applications/{$this->applicationId}/refunds", $history[1]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);
        $query2 = [];
        parse_str($history[1]['request']->getUri()->getQuery(), $query2);
        self::assertArrayHasKey('page', $query1);
        self::assertArrayHasKey('page', $query2);
        self::assertSame('1', $query1['page']);
        self::assertSame('2', $query2['page']);
    }

    public function test_YieldAllApplicationRefunds_ReturnsApplicationRefundsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $application = (new Application)->withId($this->applicationId);

        $refunds = $handler->yieldAllApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $refunds);

        $refund = $refunds->current();
        self::assertCount(3, $refunds);

        self::assertInternalType('object', $refund);
        self::assertObjectHasAttribute('id', $refund);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refund->id);

        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/refunds", $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);
        $query2 = [];
        parse_str($history[1]['request']->getUri()->getQuery(), $query2);

        self::assertArrayHasKey('page', $query1);
        self::assertArrayHasKey('page', $query2);
        self::assertSame('1', $query1['page']);
        self::assertSame('2', $query2['page']);
    }

    public function test_YieldApplicationRefundsByPage_ReturnsApplicationRefundsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $application = (new Application)->withId($this->applicationId);

        $refunds = $handler->yieldApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $refunds);

        $refund = $refunds->current();

        // Bug?:
        // Failed asserting that actual size 0 matches expected size 0
        self::assertCount(2, $refunds);

        self::assertInternalType('object', $refund);
        self::assertObjectHasAttribute('id', $refund);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refund->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/refunds", $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);

        self::assertArrayHasKey('page', $query1);
        self::assertSame('2', $query1['page']);
    }

    public function test_GetApplicationActivtionsByPage_WithSort_ReturnsSortedApplicationRefunds()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $application = (new Application)->withId($this->applicationId);

       $handler->getApplicationRefundsByPage($requestOptions, $application);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/refunds", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);

    }

    public function test_GetSingleApplicationRefund_ReturnsSingleApplicationRefund()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_get_one.json')),
        ], $history);
        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $response = $handler->getSingleApplicationRefund($application, '26d56518-e4a0-4d33-9415-be3c8d6c2661');

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame(
            "/applications/{$this->applicationId}/refunds/26d56518-e4a0-4d33-9415-be3c8d6c2661",
            $history[0]['request']->getUri()->getPath()
        );

        $result = json_decode($response->getBody(), true);

        self::assertSame('26d56518-e4a0-4d33-9415-be3c8d6c2661', $result['data']['id']);
    }

    public function test_CreateApplicationRefund_ReturnsNewlyCreatedApplicationRefund()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_get_one.json')),
        ], $history);
        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $refund = (new \Divido\MerchantSDK\Models\ApplicationRefund)
            ->withAmount(1000)
            ->withReference('D4M-njPjFRE-MxsB')
            ->withComment('Item activated')
            ->withOrderItems([
                [
                    'name' => 'Handbag',
                    'quantity' => 1,
                    'price' => 3000,
                ],
            ]);

        $response = $handler->createApplicationRefund($application, $refund);

        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/refunds", $history[0]['request']->getUri()->getPath());

        $result = json_decode($response->getBody(), true);

        self::assertSame('26d56518-e4a0-4d33-9415-be3c8d6c2661', $result['data']['id']);
    }
}

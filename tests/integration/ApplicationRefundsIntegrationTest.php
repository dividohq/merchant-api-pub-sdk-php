<?php

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\ApplicationRefunds\Handler;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ApplicationRefundsIntegrationTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    /**
     * @dataProvider provider_test_GetApplicationRefundsFromClient_ReturnsApplicationsRefunds
     */
    public function test_GetApplicationRefundsFromClient_ReturnsApplicationsRefunds($applicationModelProvided)
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        if ($applicationModelProvided) {
            $application = $this->applicationId;
        } else {
            $application = (new Application)->withId($this->applicationId);
        }

        $refunds = $sdk->getApplicationRefundsByPage($requestOptions, $application);

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

    public function test_GetApplicationRefundsByPageFromClient_ReturnsApplicationsRefunds()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $application = (new Application)->withId($this->applicationId);

        $refunds = $sdk->getApplicationRefundsByPage($requestOptions, $application);

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

    /**
     * @dataProvider provider_test_GetApplicationRefundsFromClient_ReturnsApplicationsRefunds
     */
    public function test_GetAllApplicationRefundsFromClient_ReturnsAllApplicationRefunds($applicationModelProvided)
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        if ($applicationModelProvided) {
            $application = $this->applicationId;
        } else {
            $application = (new Application)->withId($this->applicationId);
        }

        $refunds = $sdk->getAllApplicationRefunds($requestOptions, $application);

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

    public function test_YieldAllApplicationRefundsFromClient_ReturnsApplicationRefundsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $application = (new Application)->withId($this->applicationId);

        $refunds = $sdk->yieldAllApplicationRefunds($requestOptions, $application);

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

    public function test_GetApplicationActivtionsByPageFromClient_WithSort_ReturnsSortedApplicationRefunds()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $application = (new Application)->withId($this->applicationId);

        $sdk->getApplicationRefundsByPage($requestOptions, $application);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/refunds", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);
    }

    public function test_YieldApplicationRefundsByPageFromClient_ReturnsApplicationRefunds()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $refunds = $sdk->yieldApplicationRefundsByPage($requestOptions, $this->applicationId);

        self::assertInstanceOf(\Generator::class, $refunds);

        $refund = $refunds->current();
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
        self::assertSame('1', $query1['page']);
    }

    public function provider_test_GetApplicationRefundsFromClient_ReturnsApplicationsRefunds()
    {
        return [
            [true],
            [false],
        ];
    }
}

<?php
namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Response\ResponseWrapper;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ApplicationsHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    function test_GetApplicationsByPage_ReturnsApplications()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $applications = $sdk->getApplicationsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $applications);
        self::assertCount(25, $applications->getResources());
        self::assertInternalType('object', $applications->getResources()[0]);
        self::assertObjectHasAttribute('id', $applications->getResources()[0]);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $applications->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/applications', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
        self::assertSame('3', $query['page']);
    }

    function test_GetAllApplications_ReturnsAllApplications()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_2.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $requestOptions = (new ApiRequestOptions());

        $applications = $sdk->getAllApplications($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $applications);
        self::assertCount(35, $applications->getResources());
        self::assertInternalType('object', $applications->getResources()[0]);
        self::assertObjectHasAttribute('id', $applications->getResources()[0]);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $applications->getResources()[0]->id);
        self::assertSame('97ed2a20-a362-4a66-b252-237aea10ead5', $applications->getResources()[34]->id);

        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/applications', $history[0]['request']->getUri()->getPath());
        self::assertSame('/applications', $history[1]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);
        $query2 = [];
        parse_str($history[1]['request']->getUri()->getQuery(), $query2);
        self::assertArrayHasKey('page', $query1);
        self::assertArrayHasKey('page', $query2);
        self::assertSame('1', $query1['page']);
        self::assertSame('2', $query2['page']);
    }

    function test_YieldAllApplications_ReturnsApplicationsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_2.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $requestOptions = (new ApiRequestOptions());

        $applications = $sdk->yieldAllApplications($requestOptions);

        self::assertInstanceOf(\Generator::class, $applications);

        $application = $applications->current();
        self::assertCount(35, $applications);

        self::assertInternalType('object', $application);
        self::assertObjectHasAttribute('id', $application);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $application->id);

        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/applications', $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);
        $query2 = [];
        parse_str($history[1]['request']->getUri()->getQuery(), $query2);
        self::assertArrayHasKey('page', $query1);
        self::assertArrayHasKey('page', $query2);
        self::assertSame('1', $query1['page']);
        self::assertSame('2', $query2['page']);
    }

    function test_GetApplicationsByPage_WithSort_ReturnsSortedApplications()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $requestOptions = (new ApiRequestOptions())->setPage(1)->setSort('-created_at');

        $sdk->getApplicationsByPage($requestOptions);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/applications', $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);
    }

    function test_GetSingleApplication_ReturnsSingleApplication()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_get_one.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $response = $sdk->applications()->getSingleApplication('6985ef52-7d7c-457e-9a03-e98b648bf9b7');

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('/applications/6985ef52-7d7c-457e-9a03-e98b648bf9b7', $history[0]['request']->getUri()->getPath());

        $result = json_decode($response->getBody(), true);

        self::assertSame('6985ef52-7d7c-457e-9a03-e98b648bf9b7', $result['data']['id']);
    }

    function test_CreateApplication_ReturnsNewlyCreatedApplication()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_get_one.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new \Divido\MerchantSDK\Models\Application)
            ->withFinancePlanId('F335FED7A-A266-A8BF-960A-4CB56CC6DE6F')
            ->withMerchantChannelId('C47B81C83-08A8-B05A-EBD3-B9CFA1D60A07')
            ->withApplicants([
                [
                    'firstName' => 'Ann',
                    'middleNames' => '',
                    'lastName' => 'Heselden',
                    'phoneNumber' => '07512345678',
                    'email' => 'test@example.com',
                ],
            ])
            ->withOrderItems([
                [
                    'name' => 'Sofa',
                    'quantity' => 1,
                    'price' => 50000,
                ],
            ])
            ->withDepositAmount(10000);

        $response = $sdk->applications()->createApplication($application);

        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('/applications', $history[0]['request']->getUri()->getPath());

        $result = json_decode($response->getBody(), true);

        self::assertSame('6985ef52-7d7c-457e-9a03-e98b648bf9b7', $result['data']['id']);
    }

    function test_UpdateApplication_ReturnsUpdatedApplication()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_get_one.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new \Divido\MerchantSDK\Models\Application)
            ->withId('6985ef52-7d7c-457e-9a03-e98b648bf9b7')
            ->withFinancePlanId('F335FED7A-A266-A8BF-960A-4CB56CC6DE6F')
            ->withDepositAmount(10000);

        $response = $sdk->applications()->updateApplication($application);

        self::assertCount(1, $history);
        self::assertSame('PATCH', $history[0]['request']->getMethod());
        self::assertSame('/applications/6985ef52-7d7c-457e-9a03-e98b648bf9b7', $history[0]['request']->getUri()->getPath());

        $result = json_decode($response->getBody(), true);

        self::assertSame('6985ef52-7d7c-457e-9a03-e98b648bf9b7', $result['data']['id']);
    }
}

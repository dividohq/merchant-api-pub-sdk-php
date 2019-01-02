<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\ApplicationCancellations\Handler;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ApplicationCancellationsHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $applicationId = '53ad60ed-860d-4fa1-a497-03c1aea39f0a';

    public function test_GetApplicationCancellations_ReturnsApplicationsCancellations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $cancellations = $handler->getApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $cancellations);
        self::assertCount(2, $cancellations->getResources());

        self::assertInternalType('object', $cancellations->getResources()[0]);
        self::assertObjectHasAttribute('id', $cancellations->getResources()[0]);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/cancellations", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
    }

    public function test_GetApplicationCancellationsByPage_ReturnsApplicationsCancellations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $cancellations = $handler->getApplicationCancellationsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $cancellations);
        self::assertCount(2, $cancellations->getResources());

        self::assertInternalType('object', $cancellations->getResources()[0]);
        self::assertObjectHasAttribute('id', $cancellations->getResources()[0]);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/cancellations", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
    }

    public function test_GetAllApplicationCancellations_ReturnsAllApplicationCancellations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions())->setPaginated(false);

        $application = (new Application)->withId($this->applicationId);

        $cancellations = $handler->getAllApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $cancellations);
        self::assertCount(3, $cancellations->getResources());
        self::assertInternalType('object', $cancellations->getResources()[0]);
        self::assertObjectHasAttribute('id', $cancellations->getResources()[0]);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[0]->id);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-ab53abd7f950', $cancellations->getResources()[1]->id);

        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/cancellations", $history[0]['request']->getUri()->getPath());
        self::assertSame("/applications/{$this->applicationId}/cancellations", $history[1]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);
        $query2 = [];
        parse_str($history[1]['request']->getUri()->getQuery(), $query2);
        self::assertArrayHasKey('page', $query1);
        self::assertArrayHasKey('page', $query2);
        self::assertSame('1', $query1['page']);
        self::assertSame('2', $query2['page']);
    }

    public function test_YieldAllApplicationCancellations_ReturnsApplicationCancellationsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json')),
        ], $history);


        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions());

        $application = (new Application)->withId($this->applicationId);

        $cancellations = $handler->yieldAllApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $cancellations);

        $cancellation = $cancellations->current();
        self::assertCount(3, $cancellations);

        self::assertInternalType('object', $cancellation);
        self::assertObjectHasAttribute('id', $cancellation);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellation->id);

        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/cancellations", $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);
        $query2 = [];
        parse_str($history[1]['request']->getUri()->getQuery(), $query2);

        self::assertArrayHasKey('page', $query1);
        self::assertArrayHasKey('page', $query2);
        self::assertSame('1', $query1['page']);
        self::assertSame('2', $query2['page']);
    }

    public function test_YieldApplicationCancellationsByPage_ReturnsApplicationCancellationsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json')),
        ], $history);


        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $application = (new Application)->withId($this->applicationId);

        $cancellations = $handler->yieldApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $cancellations);

        $cancellation = $cancellations->current();

        // Bug?:
        // Failed asserting that actual size 0 matches expected size 0
        self::assertCount(2, $cancellations);

        self::assertInternalType('object', $cancellation);
        self::assertObjectHasAttribute('id', $cancellation);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellation->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/cancellations", $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);

        self::assertArrayHasKey('page', $query1);
        self::assertSame('2', $query1['page']);
    }

    public function test_GetApplicationCancellationsByPage_WithSort_ReturnsSortedApplicationCancellations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $application = (new Application)->withId($this->applicationId);

       $handler->getApplicationCancellationsByPage($requestOptions, $application);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/cancellations", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);

    }

    public function test_GetSingleApplicationCancellation_ReturnsSingleApplicationCancellation()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_get_one.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $response = $handler->getSingleApplicationCancellation($application, '69c08979-b727-407b-b449-6f03de02dd77');

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame(
            "/applications/{$this->applicationId}/cancellations/69c08979-b727-407b-b449-6f03de02dd77",
            $history[0]['request']->getUri()->getPath()
        );

        $result = json_decode($response->getBody(), true);

        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $result['data']['id']);
    }

    public function test_CreateApplicationCancellation_ReturnsNewlyCreatedApplicationCancellation()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_get_one.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $cancellation = (new \Divido\MerchantSDK\Models\ApplicationCancellation)
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

        $response = $handler->createApplicationCancellation($application, $cancellation);

        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/cancellations", $history[0]['request']->getUri()->getPath());

        $result = json_decode($response->getBody(), true);

        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $result['data']['id']);
    }
}

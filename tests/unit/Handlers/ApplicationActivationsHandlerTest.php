<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\ApplicationActivations\Handler;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ApplicationActivationsHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    public function test_GetApplicationActivations_ReturnsApplicationActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions());

        $activations = $handler->getApplicationActivations($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(2, $activations->getResources());

        self::assertInternalType('object', $activations->getResources()[0]);
        self::assertObjectHasAttribute('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
    }

    public function test_GetApplicationActivationsByPage_ReturnsApplicationsActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setPage(1);

        $activations = $handler->getApplicationActivationsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(2, $activations->getResources());

        self::assertInternalType('object', $activations->getResources()[0]);
        self::assertObjectHasAttribute('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
    }

    public function test_GetAllApplicationActivations_ReturnsAllApplicationActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $requestOptions = (new ApiRequestOptions())->setPaginated(false);
        $activations = $handler->getAllApplicationActivations($requestOptions, $application);


        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(3, $activations->getResources());
        self::assertInternalType('object', $activations->getResources()[0]);
        self::assertObjectHasAttribute('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);
        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $activations->getResources()[1]->id);
        self::assertSame('69c08979-b727-407b-b449-6f03de02dd78', $activations->getResources()[2]->id);


        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[1]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);
        $query2 = [];
        parse_str($history[1]['request']->getUri()->getQuery(), $query2);
        self::assertArrayHasKey('page', $query1);
        self::assertArrayHasKey('page', $query2);
        self::assertSame('1', $query1['page']);
        self::assertSame('2', $query2['page']);
    }

    public function test_YieldAllApplicationActivations_ReturnsApplicationActivationsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setPaginated(false);

        $activations = $handler->yieldAllApplicationActivations($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $activations);

        $activation = $activations->current();
        self::assertCount(3, $activations);

        self::assertInternalType('object', $activation);
        self::assertObjectHasAttribute('id', $activation);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activation->id);

        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);
        $query2 = [];
        parse_str($history[1]['request']->getUri()->getQuery(), $query2);

        self::assertArrayHasKey('page', $query1);
        self::assertArrayHasKey('page', $query2);
        self::assertSame('1', $query1['page']);
        self::assertSame('2', $query2['page']);
    }

    public function test_YieldApplicationActivationsByPage_ReturnsApplicationActivationsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setPaginated(true);

        $activations = $handler->yieldApplicationActivations($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $activations);

        $activation = $activations->current();
        self::assertCount(2, $activations);

        self::assertInternalType('object', $activation);
        self::assertObjectHasAttribute('id', $activation);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activation->id);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());

        $query1 = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query1);

        self::assertArrayHasKey('page', $query1);
        self::assertSame('1', $query1['page']);
    }

    public function test_GetApplicationActivtionsByPage_WithSort_ReturnsSortedApplicationActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

       $handler->getApplicationActivationsByPage($requestOptions, $application);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);
    }

    public function test_GetSingleApplicationActivation_ReturnsSingleApplicationActivation()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_get_one.json')),
        ], $history);
        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $response = $handler->getSingleApplicationActivation($application, '69c08979-b727-407b-b449-6f03de02dd77');

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame(
            "/applications/{$this->applicationId}/activations/69c08979-b727-407b-b449-6f03de02dd77",
            $history[0]['request']->getUri()->getPath()
        );

        $result = json_decode($response->getBody(), true);

        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $result['data']['id']);
    }

    public function test_CreateApplicationActivation_ReturnsNewlyCreatedApplicationActivation()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_get_one.json')),
        ], $history);
        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $application = (new Application)->withId($this->applicationId);

        $activation = (new \Divido\MerchantSDK\Models\ApplicationActivation)
            ->withAmount(1000)
            ->withReference('D4M-njPjFRE-MxsB')
            ->withComment('Item activated')
            ->withOrderItems([
                [
                    'name' => 'Handbag',
                    'quantity' => 1,
                    'price' => 3000,
                ],
            ])
            ->withDeliveryMethod('delivery')
            ->withTrackingNumber('2m987-769m-27i');

        $response = $handler->createApplicationActivation($application, $activation);

        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());

        $result = json_decode($response->getBody(), true);

        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $result['data']['id']);
    }
}

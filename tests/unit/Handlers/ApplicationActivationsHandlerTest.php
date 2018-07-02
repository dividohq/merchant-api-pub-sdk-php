<?php
namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ApplicationActivationsHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    function test_GetApplicationActivationsByPage_ReturnsApplicationsActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new Application)->withId($this->applicationId);

        $activations = $sdk->application_activations()->getApplicationActivationsByPage($application, 1);

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

    function test_GetAllApplicationActivations_ReturnsAllApplicationActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new Application)->withId($this->applicationId);

        $activations = $sdk->application_activations()->getAllApplicationActivations($application);

        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(2, $activations->getResources());
        self::assertInternalType('object', $activations->getResources()[0]);
        self::assertObjectHasAttribute('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);
        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $activations->getResources()[1]->id);

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

    function test_YieldAllApplicationActivations_ReturnsApplicationActivationsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new Application)->withId($this->applicationId);

        $activations = $sdk->application_activations()->yieldAllApplicationActivations($application, 2, 'hello');

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

    function test_GetApplicationActivtionsByPage_WithSort_ReturnsSortedApplicationActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
        ], $history);

        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new Application)->withId($this->applicationId);

        $sdk->application_activations()->getApplicationActivationsByPage($application, 1, '-created_at');

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);

    }

    function test_GetSingleApplicationActivation_ReturnsSingleApplicationActivation()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_get_one.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new Application)->withId($this->applicationId);

        $response = $sdk->application_activations()->getSingleApplicationActivation($application, '69c08979-b727-407b-b449-6f03de02dd77');

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame(
            "/applications/{$this->applicationId}/activations/69c08979-b727-407b-b449-6f03de02dd77",
            $history[0]['request']->getUri()->getPath()
        );

        $result = json_decode($response->getBody(), true);

        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $result['data']['id']);
    }

    function test_CreateApplicationActivation_ReturnsNewlyCreatedApplicationActivation()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_get_one.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new Application)->withId($this->applicationId);

        // THIS HAS ALL CHANGED!!!
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

        $response = $sdk->application_activations()->createApplicationActivation($application, $activation);

        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());

        $result = json_decode($response->getBody(), true);

        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $result['data']['id']);
    }
}
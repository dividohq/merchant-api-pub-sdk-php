<?php

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\ApplicationCancellations\Handler;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ApplicationCancellationsIntegrationTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $applicationId = '53ad60ed-860d-4fa1-a497-03c1aea39f0a';

    /**
     * @dataProvider provider_test_GetApplicationCancellationsFromClient_ReturnsApplicationsCancellations
     */
    public function test_GetApplicationCancellationsFromClient_ReturnsApplicationsCancellations($applicationModelProvided)
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        if ($applicationModelProvided) {
            $application = $this->applicationId;
        } else {
            $application = (new Application)->withId($this->applicationId);
        }

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $cancellations = $sdk->getApplicationCancellationsByPage($requestOptions, $application);

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

    public function provider_test_GetApplicationCancellationsFromClient_ReturnsApplicationsCancellations()
    {
        return [
            [true],
            [false],
        ];
    }

    public function test_GetApplicationCancellationsByPageFromClient_ReturnsApplicationsCancellations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $application = (new Application)->withId($this->applicationId);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $cancellations = $sdk->getApplicationCancellationsByPage($requestOptions, $application);

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

    /**
     * @dataProvider provider_test_GetAllApplicationCancellationsFromClient_ReturnsAllApplicationCancellations
     */
    public function test_GetAllApplicationCancellationsFromClient_ReturnsAllApplicationCancellations($applicationModelProvided)
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPaginated(false);

        if ($applicationModelProvided) {
            $application = $this->applicationId;
        } else {
            $application = (new Application)->withId($this->applicationId);
        }

        $cancellations = $sdk->getAllApplicationCancellations($requestOptions, $application);

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

    public function provider_test_GetAllApplicationCancellationsFromClient_ReturnsAllApplicationCancellations()
    {
        return [
            [true],
            [false],
        ];
    }

    public function test_YieldAllApplicationCancellationsFromClient_ReturnsApplicationCancellationsGenerator()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json')),
        ], $history);


        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $application = (new Application)->withId($this->applicationId);

        $cancellations = $sdk->yieldAllApplicationCancellations($requestOptions, $application);

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

    public function test_GetApplicationCancellationsByPageFromClient_WithSort_ReturnsSortedApplicationCancellations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $application = (new Application)->withId($this->applicationId);

        $sdk->getApplicationCancellationsByPage($requestOptions, $application);

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/cancellations", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('sort', $query);
        self::assertSame('-created_at', $query['sort']);
    }

    public function test_YieldApplicationCancellationsByPageFromClient_ReturnsApplicationCancellations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $cancellations = $sdk->yieldApplicationCancellationsByPage($requestOptions, $this->applicationId);

        self::assertInstanceOf(\Generator::class, $cancellations);

        $cancellation = $cancellations->current();
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
        self::assertSame('1', $query1['page']);
    }
}

<?php

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ApplicationActivationsIntegrationTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    /**
     * @dataProvider provider_test_GetApplicationActivationsByPageFromClient_ReturnsApplicationActivationsByPage
     */
    public function test_GetApplicationActivationsByPageFromClient_ReturnsApplicationActivationsByPage($applicationModelProvided)
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
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

        $activations = $sdk->getApplicationActivationsByPage($requestOptions, $application);

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

    public function provider_test_GetApplicationActivationsByPageFromClient_ReturnsApplicationActivationsByPage()
    {
        return [
            [true],
            [false],
        ];
    }

    public function test_GetApplicationActivationsFromClient_ReturnsApplicationActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $activations = $sdk->getAllApplicationActivations($requestOptions, $this->applicationId);

        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(3, $activations->getResources());

        self::assertInternalType('object', $activations->getResources()[0]);
        self::assertObjectHasAttribute('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);

        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/activations", $history[0]['request']->getUri()->getPath());

        $query = [];
        parse_str($history[0]['request']->getUri()->getQuery(), $query);

        self::assertArrayHasKey('page', $query);
    }

    public function test_YieldAllApplicationActivationsFromClient_ReturnsApplicationActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $activations = $sdk->yieldAllApplicationActivations($requestOptions, $this->applicationId);

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

    public function test_YieldApplicationActivationsByPageFromClient_ReturnsApplicationActivations()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json')),
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $activations = $sdk->yieldApplicationActivationsByPage($requestOptions, $this->applicationId);

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
}

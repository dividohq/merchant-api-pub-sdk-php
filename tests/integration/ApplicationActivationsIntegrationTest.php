<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;

class ApplicationActivationsIntegrationTest extends MerchantSDKTestCase
{
    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    /**
     * @dataProvider provider_test_GetApplicationActivationsByPageFromClient_ReturnsApplicationActivationsByPage
     */
    public function test_GetApplicationActivationsByPageFromClient_ReturnsApplicationActivationsByPage($applicationModelProvided)
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        if ($applicationModelProvided) {
            $application = $this->applicationId;
        } else {
            $application = (new Application)->withId($this->applicationId);
        }

        $activations = $sdk->getApplicationActivationsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(2, $activations->getResources());

        self::assertIsObject($activations->getResources()[0]);
        self::assertObjectHasProperty('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);
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
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json'))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $activations = $sdk->getAllApplicationActivations($requestOptions, $this->applicationId);

        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(3, $activations->getResources());

        self::assertIsObject($activations->getResources()[0]);
        self::assertObjectHasProperty('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);
    }

    public function test_YieldAllApplicationActivationsFromClient_ReturnsApplicationActivations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json'))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $activations = $sdk->yieldAllApplicationActivations($requestOptions, $this->applicationId);

        self::assertInstanceOf(\Generator::class, $activations);

        $activation = $activations->current();
        self::assertCount(3, iterator_to_array($activations, false));

        self::assertIsObject($activation);
        self::assertObjectHasProperty('id', $activation);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activation->id);
    }

    public function test_YieldApplicationActivationsByPageFromClient_ReturnsApplicationActivations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_1.json'))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_activations_page_2.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $activations = $sdk->yieldApplicationActivationsByPage($requestOptions, $this->applicationId);

        self::assertInstanceOf(\Generator::class, $activations);

        $activation = $activations->current();
        self::assertCount(2, iterator_to_array($activations, false));

        self::assertIsObject($activation);
        self::assertObjectHasProperty('id', $activation);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activation->id);
    }
}

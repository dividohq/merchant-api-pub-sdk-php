<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\ApplicationActivations\Handler;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class ApplicationActivationsHandlerTest extends MerchantSDKTestCase
{
    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    public function test_GetApplicationActivations_ReturnsApplicationActivations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_page_1.json'
            ))
        );

        $expectedUri = '-merchant-api-pub-http-host-' .
            '/applications' .
            '/90a25b24-2f53-4c80-aba8-9787c68e4c1d' .
            '/activations' .
            '?page=1&sort=-created_at';
        $requestFactory = self::createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')
            ->with(AbstractHttpHandler::GET_METHOD, $expectedUri, ['X-Divido-Api-Key' => 'divido'], null)
            ->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application)->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $activations = $handler->getApplicationActivations($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(2, $activations->getResources());

        self::assertIsObject($activations->getResources()[0]);
        self::assertObjectHasAttribute('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);
    }

    public function test_GetApplicationActivationsByPage_ReturnsApplicationsActivations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_page_1.json'
            ))
        );

        $requestFactory = self::createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application)->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions());

        $activations = $handler->getApplicationActivationsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(2, $activations->getResources());

        self::assertIsObject($activations->getResources()[0]);
        self::assertObjectHasAttribute('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);
    }

    public function test_GetAllApplicationActivations_ReturnsAllApplicationActivations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_page_2.json'
            ))
        );

        $requestFactory = self::createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application)->withId($this->applicationId);

        $requestOptions = (new ApiRequestOptions())->setPaginated(false);
        $activations = $handler->getAllApplicationActivations($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $activations);
        self::assertCount(3, $activations->getResources());
        self::assertIsObject($activations->getResources()[0]);
        self::assertObjectHasAttribute('id', $activations->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activations->getResources()[0]->id);
        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $activations->getResources()[1]->id);
        self::assertSame('69c08979-b727-407b-b449-6f03de02dd78', $activations->getResources()[2]->id);
    }

    public function test_YieldAllApplicationActivations_ReturnsApplicationActivationsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_page_2.json'
            ))
        );

        $requestFactory = self::createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application)->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setPaginated(false);

        $activations = $handler->yieldAllApplicationActivations($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $activations);

        $activation = $activations->current();
        self::assertCount(3, $activations);

        self::assertIsObject($activation);
        self::assertObjectHasAttribute('id', $activation);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activation->id);
    }

    public function test_YieldApplicationActivationsByPage_ReturnsApplicationActivationsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_page_2.json'
            ))
        );

        $requestFactory = self::createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application)->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setPaginated(true);

        $activations = $handler->yieldApplicationActivations($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $activations);

        $activation = $activations->current();
        self::assertCount(2, $activations);

        self::assertIsObject($activation);
        self::assertObjectHasAttribute('id', $activation);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $activation->id);
    }

    public function test_GetSingleApplicationActivation_ReturnsSingleApplicationActivation()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_get_one.json'
            ))
        );

        $requestFactory = self::createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application)->withId($this->applicationId);

        $response = $handler->getSingleApplicationActivation($application, '69c08979-b727-407b-b449-6f03de02dd77');

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $result['data']['id']);
    }

    public function test_CreateApplicationActivation_ReturnsNewlyCreatedApplicationActivation()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(201, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_activations_get_one.json'
            ))
        );

        $requestFactory = self::createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

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

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $result['data']['id']);
    }
}

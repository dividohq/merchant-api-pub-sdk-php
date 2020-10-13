<?php

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Http\Message\RequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class ApplicationCancellationsIntegrationTest extends MerchantSDKTestCase
{
    private $applicationId = '53ad60ed-860d-4fa1-a497-03c1aea39f0a';

    /**
     * @dataProvider provider_test_GetApplicationCancellationsFromClient_ReturnsApplicationsCancellations
     */
    public function test_GetApplicationCancellationsFromClient_ReturnsApplicationsCancellations($applicationModelProvided): void
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        if ($applicationModelProvided) {
            $application = $this->applicationId;
        } else {
            $application = (new Application)->withId($this->applicationId);
        }

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $cancellations = $sdk->getApplicationCancellationsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $cancellations);
        self::assertCount(2, $cancellations->getResources());

        self::assertIsObject($cancellations->getResources()[0]);
        self::assertObjectHasAttribute('id', $cancellations->getResources()[0]);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[0]->id);
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
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $application = (new Application)->withId($this->applicationId);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $cancellations = $sdk->getApplicationCancellationsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $cancellations);
        self::assertCount(2, $cancellations->getResources());

        self::assertIsObject($cancellations->getResources()[0]);
        self::assertObjectHasAttribute('id', $cancellations->getResources()[0]);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[0]->id);
    }

    /**
     * @dataProvider provider_test_GetAllApplicationCancellationsFromClient_ReturnsAllApplicationCancellations
     */
    public function test_GetAllApplicationCancellationsFromClient_ReturnsAllApplicationCancellations($applicationModelProvided): void
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json')),
        );
        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPaginated(false);

        if ($applicationModelProvided) {
            $application = $this->applicationId;
        } else {
            $application = (new Application)->withId($this->applicationId);
        }

        $cancellations = $sdk->getAllApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $cancellations);
        self::assertCount(3, $cancellations->getResources());
        self::assertIsObject($cancellations->getResources()[0]);
        self::assertObjectHasAttribute('id', $cancellations->getResources()[0]);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[0]->id);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-ab53abd7f950', $cancellations->getResources()[1]->id);
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
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json')),
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $application = (new Application)->withId($this->applicationId);

        $cancellations = $sdk->yieldAllApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $cancellations);

        $cancellation = $cancellations->current();
        self::assertCount(3, $cancellations);

        self::assertIsObject($cancellation);
        self::assertObjectHasAttribute('id', $cancellation);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellation->id);
    }

    public function test_GetApplicationCancellationsByPageFromClient_WithSort_ReturnsSortedApplicationCancellations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $application = (new Application)->withId($this->applicationId);

        $sdk->getApplicationCancellationsByPage($requestOptions, $application);

        self::addToAssertionCount(1);
    }

    public function test_YieldApplicationCancellationsByPageFromClient_ReturnsApplicationCancellations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json')),
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json')),
        );
        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $cancellations = $sdk->yieldApplicationCancellationsByPage($requestOptions, $this->applicationId);

        self::assertInstanceOf(\Generator::class, $cancellations);

        $cancellation = $cancellations->current();
        self::assertCount(2, $cancellations);

        self::assertIsObject($cancellation);
        self::assertObjectHasAttribute('id', $cancellation);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellation->id);
    }
}

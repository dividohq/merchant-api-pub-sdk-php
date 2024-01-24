<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\ApplicationCancellations\Handler;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class ApplicationCancellationsHandlerTest extends MerchantSDKTestCase
{
    private $applicationId = '53ad60ed-860d-4fa1-a497-03c1aea39f0a';

    public function test_GetApplicationCancellations_ReturnsApplicationCancellations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions());

        $cancellations = $handler->getApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $cancellations);
        self::assertCount(2, $cancellations->getResources());

        self::assertIsObject($cancellations->getResources()[0]);
        self::assertObjectHasProperty('id', $cancellations->getResources()[0]);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[0]->id);
    }

    public function test_GetApplicationCancellationsByPage_ReturnsApplicationsCancellations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions());

        $cancellations = $handler->getApplicationCancellationsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $cancellations);
        self::assertCount(2, $cancellations->getResources());

        self::assertIsObject($cancellations->getResources()[0]);
        self::assertObjectHasProperty('id', $cancellations->getResources()[0]);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[0]->id);
    }

    public function test_GetAllApplicationCancellations_ReturnsAllApplicationCancellations()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);

        $requestOptions = (new ApiRequestOptions())->setPaginated(false);
        $cancellations = $handler->getAllApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $cancellations);
        self::assertCount(3, $cancellations->getResources());
        self::assertIsObject($cancellations->getResources()[0]);
        self::assertObjectHasProperty('id', $cancellations->getResources()[0]);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[0]->id);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-ab53abd7f950', $cancellations->getResources()[1]->id);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellations->getResources()[2]->id);
    }

    public function test_YieldAllApplicationCancellations_ReturnsApplicationCancellationsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setPaginated(false);

        $cancellations = $handler->yieldAllApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $cancellations);

        $cancellation = $cancellations->current();
        self::assertCount(3, iterator_to_array($cancellations, false));

        self::assertIsObject($cancellation);
        self::assertObjectHasProperty('id', $cancellation);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellation->id);
    }

    public function test_YieldApplicationCancellationsByPage_ReturnsApplicationCancellationsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setPaginated(true);

        $cancellations = $handler->yieldApplicationCancellations($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $cancellations);

        $cancellation = $cancellations->current();
        self::assertCount(2, iterator_to_array($cancellations, false));

        self::assertIsObject($cancellation);
        self::assertObjectHasProperty('id', $cancellation);
        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $cancellation->id);
    }

    public function test_GetSingleApplicationCancellation_ReturnsSingleApplicationCancellation()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_get_one.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);

        $response = $handler->getSingleApplicationCancellation($application, '5d1b94f5-3a7f-4f70-be6e-bb53abd7f955');

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $result['data']['id']);
    }

    public function test_CreateApplicationCancellation_ReturnsNewlyCreatedApplicationCancellation()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(201, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_cancellations_get_one.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $mockStreamFactory = $this->createMock(StreamFactoryInterface::class);
        $mockStreamFactory->method('createStream')->willReturn(
            $this->createMock(StreamInterface::class)
        );

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory, $mockStreamFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);

        $cancellation = (new \Divido\MerchantSDK\Models\ApplicationCancellation())
            ->withAmount(1000)
            ->withReference('D4M-njPjFRE-MxsB')
            ->withComment('Item cancelled')
            ->withOrderItems([
                [
                    'name' => 'Handbag',
                    'quantity' => 1,
                    'price' => 3000,
                ],
            ])
            ->withReason('Too Big');

        $response = $handler->createApplicationCancellation($application, $cancellation);

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('5d1b94f5-3a7f-4f70-be6e-bb53abd7f955', $result['data']['id']);
    }
}

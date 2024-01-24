<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\ApplicationRefunds\Handler;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class ApplicationRefundsHandlerTest extends MerchantSDKTestCase
{
    private $applicationId = '53ad60ed-860d-4fa1-a497-03c1aea39f0a';

    public function test_GetApplicationRefunds_ReturnsApplicationRefunds()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions());

        $refunds = $handler->getApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $refunds);
        self::assertCount(2, $refunds->getResources());

        self::assertIsObject($refunds->getResources()[0]);
        self::assertObjectHasProperty('id', $refunds->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refunds->getResources()[0]->id);
    }

    public function test_GetApplicationRefundsByPage_ReturnsApplicationsRefunds()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions());

        $refunds = $handler->getApplicationRefundsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $refunds);
        self::assertCount(2, $refunds->getResources());

        self::assertIsObject($refunds->getResources()[0]);
        self::assertObjectHasProperty('id', $refunds->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refunds->getResources()[0]->id);
    }

    public function test_GetAllApplicationRefunds_ReturnsAllApplicationRefunds()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);

        $requestOptions = (new ApiRequestOptions())->setPaginated(false);
        $refunds = $handler->getAllApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $refunds);
        self::assertCount(3, $refunds->getResources());
        self::assertIsObject($refunds->getResources()[0]);
        self::assertObjectHasProperty('id', $refunds->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refunds->getResources()[0]->id);
        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $refunds->getResources()[1]->id);
        self::assertSame('69c08979-b727-407b-b449-6f03de02dd78', $refunds->getResources()[2]->id);
    }

    public function test_YieldAllApplicationRefunds_ReturnsApplicationRefundsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setPaginated(false);

        $refunds = $handler->yieldAllApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $refunds);

        $refund = $refunds->current();
        self::assertCount(3, iterator_to_array($refunds, false));

        self::assertIsObject($refund);
        self::assertObjectHasProperty('id', $refund);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refund->id);
    }

    public function test_YieldApplicationRefundsByPage_ReturnsApplicationRefundsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);
        $requestOptions = (new ApiRequestOptions())->setPaginated(true);

        $refunds = $handler->yieldApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $refunds);

        $refund = $refunds->current();
        self::assertCount(2, iterator_to_array($refunds, false));

        self::assertIsObject($refund);
        self::assertObjectHasProperty('id', $refund);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refund->id);
    }

    public function test_GetSingleApplicationRefund_ReturnsSingleApplicationRefund()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_get_one.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application())->withId($this->applicationId);

        $response = $handler->getSingleApplicationRefund($application, '97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b');

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('26d56518-e4a0-4d33-9415-be3c8d6c2661', $result['data']['id']);
    }

    public function test_CreateApplicationRefund_ReturnsNewlyCreatedApplicationRefund()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(201, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_refunds_get_one.json'
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

        $refund = (new \Divido\MerchantSDK\Models\ApplicationRefund())
            ->withAmount(1000)
            ->withReference('D4M-njPjFRE-MxsB')
            ->withComment('Item refunded')
            ->withOrderItems([
                [
                    'name' => 'Handbag',
                    'quantity' => 1,
                    'price' => 3000,
                ],
            ])
            ->withReason('Too Small');

        $response = $handler->createApplicationRefund($application, $refund);

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('26d56518-e4a0-4d33-9415-be3c8d6c2661', $result['data']['id']);
    }
}

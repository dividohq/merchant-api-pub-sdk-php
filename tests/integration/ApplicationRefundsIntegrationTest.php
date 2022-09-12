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

class ApplicationRefundsIntegrationTest extends MerchantSDKTestCase
{
    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    /**
     * @dataProvider provider_test_GetApplicationRefundsFromClient_ReturnsApplicationsRefunds
     */
    public function test_GetApplicationRefundsFromClient_ReturnsApplicationsRefunds($applicationModelProvided)
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'))
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        if ($applicationModelProvided) {
            $application = $this->applicationId;
        } else {
            $application = (new Application)->withId($this->applicationId);
        }

        $refunds = $sdk->getApplicationRefundsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $refunds);
        self::assertCount(2, $refunds->getResources());

        self::assertIsObject($refunds->getResources()[0]);
        self::assertObjectHasAttribute('id', $refunds->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refunds->getResources()[0]->id);

    }

    public function test_GetApplicationRefundsByPageFromClient_ReturnsApplicationsRefunds()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'))
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $application = (new Application)->withId($this->applicationId);

        $refunds = $sdk->getApplicationRefundsByPage($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $refunds);
        self::assertCount(2, $refunds->getResources());

        self::assertIsObject($refunds->getResources()[0]);
        self::assertObjectHasAttribute('id', $refunds->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refunds->getResources()[0]->id);
    }

    /**
     * @dataProvider provider_test_GetApplicationRefundsFromClient_ReturnsApplicationsRefunds
     */
    public function test_GetAllApplicationRefundsFromClient_ReturnsAllApplicationRefunds($applicationModelProvided)
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_2.json'))
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        if ($applicationModelProvided) {
            $application = $this->applicationId;
        } else {
            $application = (new Application)->withId($this->applicationId);
        }

        $refunds = $sdk->getAllApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(ResponseWrapper::class, $refunds);
        // self::assertCount(2, $refunds->getResources());
        self::assertIsObject($refunds->getResources()[0]);
        self::assertObjectHasAttribute('id', $refunds->getResources()[0]);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refunds->getResources()[0]->id);
        self::assertSame('69c08979-b727-407b-b449-6f03de02dd77', $refunds->getResources()[1]->id);
    }

    public function test_YieldAllApplicationRefundsFromClient_ReturnsApplicationRefundsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_2.json'))
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $application = (new Application)->withId($this->applicationId);

        $refunds = $sdk->yieldAllApplicationRefunds($requestOptions, $application);

        self::assertInstanceOf(\Generator::class, $refunds);

        $refund = $refunds->current();
        self::assertCount(3, $refunds);

        self::assertIsObject($refund);
        self::assertObjectHasAttribute('id', $refund);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refund->id);
    }

    public function test_GetApplicationActivtionsByPageFromClient_WithSort_ReturnsSortedApplicationRefunds()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'))
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setSort('-created_at');

        $application = (new Application)->withId($this->applicationId);

        $sdk->getApplicationRefundsByPage($requestOptions, $application);

        self::addToAssertionCount(1);
    }

    public function test_YieldApplicationRefundsByPageFromClient_ReturnsApplicationRefunds()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_1.json'))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_refunds_page_2.json'))
        );

        $requestFactory = self::createMock(RequestFactory::class);
        $requestFactory->method('createRequest')->willReturn(self::createMock(RequestInterface::class));

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $refunds = $sdk->yieldApplicationRefundsByPage($requestOptions, $this->applicationId);

        self::assertInstanceOf(\Generator::class, $refunds);

        $refund = $refunds->current();
        self::assertCount(2, $refunds);

        self::assertIsObject($refund);
        self::assertObjectHasAttribute('id', $refund);
        self::assertSame('97ca1476-2c9c-4ca2-b4c6-1f41f2ecdf5b', $refund->id);
    }

    public function provider_test_GetApplicationRefundsFromClient_ReturnsApplicationsRefunds()
    {
        return [
            [true],
            [false],
        ];
    }
}

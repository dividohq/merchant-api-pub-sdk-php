<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Exceptions\MerchantApiBadResponseException;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;

class ApplicationsIntegrationTest extends MerchantSDKTestCase
{
    public function test_GetApplicationsFromClient_ReturnsApplications()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $applications = $sdk->getApplicationsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $applications);
        self::assertCount(25, $applications->getResources());
        self::assertIsObject($applications->getResources()[0]);
        self::assertObjectHasProperty('id', $applications->getResources()[0]);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $applications->getResources()[0]->id);
    }

    public function test_GetApplicationsFromClient_WithInvalidRequest_ThrowsException()
    {
        $merchantApiBadResponseExceptionThrown = false;

        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(400, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_all_error.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = new ApiRequestOptions();

        try {
            $sdk->getApplicationsByPage($requestOptions);
        } catch (\Exception $e) {
            if ($e instanceof MerchantApiBadResponseException) {
                $merchantApiBadResponseExceptionThrown = true;

                $context = (object) [
                    'property' => 'sort',
                    'more' => 'Foo more',
                ];

                self::assertEquals($context, $e->getContext());
            }

            self::assertSame('payload property missing or invalid', $e->getMessage());
        }

        self::assertTrue($merchantApiBadResponseExceptionThrown);
    }

    public function test_GetApplicationsByPageFromClient_ReturnsApplications()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $applications = $sdk->getApplicationsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $applications);
        self::assertCount(25, $applications->getResources());
        self::assertIsObject($applications->getResources()[0]);
        self::assertObjectHasProperty('id', $applications->getResources()[0]);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $applications->getResources()[0]->id);
    }

    public function test_GetAllApplicationsFromClient_ReturnsAllApplications()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json'))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_2.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $applications = $sdk->getAllApplications($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $applications);
        self::assertCount(35, $applications->getResources());
        self::assertIsObject($applications->getResources()[0]);
        self::assertObjectHasProperty('id', $applications->getResources()[0]);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $applications->getResources()[0]->id);
        self::assertSame('97ed2a20-a362-4a66-b252-237aea10ead5', $applications->getResources()[34]->id);
    }

    public function test_YieldAllApplicationsFromClient_ReturnsApplicationsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json'))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_2.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $applications = $sdk->yieldAllApplications($requestOptions);

        self::assertInstanceOf(\Generator::class, $applications);

        $application = $applications->current();
        self::assertCount(35, iterator_to_array($applications, false));

        self::assertIsObject($application);
        self::assertObjectHasProperty('id', $application);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $application->id);
    }

    public function test_GetApplicationsByPageFromClient_WithSort_ReturnsSortedApplications()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(1)->setSort('-created_at');

        $sdk->getApplicationsByPage($requestOptions);

        self::addToAssertionCount(1);

    }

    public function test_YieldApplicationsByPageFromClient_ReturnsApplications()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_1.json'))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_page_2.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $applications = $sdk->yieldApplicationsByPage($requestOptions);

        self::assertInstanceOf(\Generator::class, $applications);

        $application = $applications->current();
        self::assertCount(25, iterator_to_array($applications, false));

        self::assertIsObject($application);
        self::assertObjectHasProperty('id', $application);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $application->id);
    }
}

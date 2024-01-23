<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\Applications\Handler;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class ApplicationsHandlerTest extends MerchantSDKTestCase
{
    public function test_GetApplications_ReturnsApplications()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $applications = $handler->getApplications($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $applications);
        self::assertCount(25, $applications->getResources());
        self::assertIsObject($applications->getResources()[0]);
        self::assertObjectHasProperty('id', $applications->getResources()[0]);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $applications->getResources()[0]->id);
    }

    public function test_GetApplicationsByPage_ReturnsApplications()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $applications = $handler->getApplicationsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $applications);
        self::assertCount(25, $applications->getResources());
        self::assertIsObject($applications->getResources()[0]);
        self::assertObjectHasProperty('id', $applications->getResources()[0]);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $applications->getResources()[0]->id);
    }

    public function test_GetAllApplications_ReturnsAllApplications()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $applications = $handler->getAllApplications($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $applications);
        self::assertCount(35, $applications->getResources());
        self::assertIsObject($applications->getResources()[0]);
        self::assertObjectHasProperty('id', $applications->getResources()[0]);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $applications->getResources()[0]->id);
        self::assertSame('97ed2a20-a362-4a66-b252-237aea10ead5', $applications->getResources()[34]->id);
    }

    public function test_GetAllApplications_WithFilters_MakesApiCallWithFilters()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $filters = [
            'current_status' => 'deposit-paid',
            'created_after' => '2015-01-01',
        ];

        $requestOptions = (new ApiRequestOptions())
            ->setFilters($filters);

        $applications = $handler->getAllApplications($requestOptions);

        // self::assertSame(http_build_query($data), $history[0]['request']->getUri()->getQuery());

        self::addToAssertionCount(1);
    }

    public function test_YieldAllApplications_ReturnsApplicationsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $applications = $handler->yieldAllApplications($requestOptions);

        self::assertInstanceOf(\Generator::class, $applications);

        $application = $applications->current();
        self::assertCount(35, iterator_to_array($applications, false));

        self::assertIsObject($application);
        self::assertObjectHasProperty('id', $application);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $application->id);
    }

    public function test_YieldApplicationsByPage_ReturnsApplicationsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_1.json'
            ))
        );
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_2.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $applications = $handler->yieldApplications($requestOptions);

        self::assertInstanceOf(\Generator::class, $applications);

        $application = $applications->current();

        self::assertCount(25, iterator_to_array($applications, false));

        self::assertIsObject($application);
        self::assertObjectHasProperty('id', $application);
        self::assertSame('0074dd19-dbba-4d80-bdb7-c4a2176cb399', $application->id);
    }

    public function test_GetApplicationsByPage_WithSort_ReturnsSortedApplications()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = new ApiRequestOptions();

        $handler->getApplicationsByPage($requestOptions);

        self::addToAssertionCount(1);
    }

    public function test_GetSingleApplication_ReturnsSingleApplication()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_get_one.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $response = $handler->getSingleApplication('6985ef52-7d7c-457e-9a03-e98b648bf9b7');

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('6985ef52-7d7c-457e-9a03-e98b648bf9b7', $result['data']['id']);
    }

    public function test_CreateApplication_ReturnsNewlyCreatedApplication()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(201, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_get_one.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $mockStreamFactory = $this->createMock(StreamFactoryInterface::class);
        $mockStreamFactory->method('createStream')->willReturn(
            $this->createMock(StreamInterface::class)
        );

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory, $mockStreamFactory);

        $handler = new Handler($wrapper);

        $application = (new \Divido\MerchantSDK\Models\Application())
            ->withCountryId('GB')
            ->withCurrencyId('GBP')
            ->withLanguageId('EN')
            ->withFinancePlanId('F335FED7A-A266-A8BF-960A-4CB56CC6DE6F')
            ->withMerchantChannelId('C47B81C83-08A8-B05A-EBD3-B9CFA1D60A07')
            ->withApplicants([
                [
                    'firstName' => 'Ann',
                    'middleNames' => '',
                    'lastName' => 'Heselden',
                    'phoneNumber' => '07512345678',
                    'email' => 'test@example.com',
                ],
            ])
            ->withOrderItems([
                [
                    'name' => 'Sofa',
                    'quantity' => 1,
                    'price' => 50000,
                ],
            ])
            ->withDepositAmount(10000)
            ->withDepositPercentage(0.02)
            ->withFinalisationRequired(false)
            ->withMerchantReference("foo-ref")
            ->withUrls([
                'merchant_redirect_url' => 'foo-with-merchant-redirect-url',
                'merchant_checkout_url' => 'foo-with-merchant-checkout-url',
                'merchant_response_url' => 'foo-with-merchant-response-url',
            ])
            ->withMetadata([
                'foo' => 'bar',
            ]);

        $response = $handler->createApplication($application);

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('6985ef52-7d7c-457e-9a03-e98b648bf9b7', $result['data']['id']);
    }

    public function test_CreateApplication_WithHmac_ReturnsNewlyCreatedApplication()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(201, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_get_one.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $mockStreamFactory = $this->createMock(StreamFactoryInterface::class);
        $mockStreamFactory->method('createStream')->willReturn(
            $this->createMock(StreamInterface::class)
        );

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory, $mockStreamFactory);

        $handler = new Handler($wrapper);

        $application = new \Divido\MerchantSDK\Models\Application();

        $response = $handler->createApplication(
            $application,
            [],
            ['X-Divido-Hmac-Sha256' => 'EkDuBPzoelFHGYEmF30hU31G2roTr4OFoxI9efPxjKY=']
        );

        // self::assertSame('EkDuBPzoelFHGYEmF30hU31G2roTr4OFoxI9efPxjKY=', $history[0]['request']->getHeaderLine('X-Divido-Hmac-Sha256'));

        self::addToAssertionCount(1);

    }

    public function test_UpdateApplication_ReturnsUpdatedApplication()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/applications_get_one.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $mockStreamFactory = $this->createMock(StreamFactoryInterface::class);
        $mockStreamFactory->method('createStream')->willReturn(
            $this->createMock(StreamInterface::class)
        );

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory, $mockStreamFactory);

        $handler = new Handler($wrapper);

        $application = (new \Divido\MerchantSDK\Models\Application())
            ->withId('6985ef52-7d7c-457e-9a03-e98b648bf9b7')
            ->withFinancePlanId('F335FED7A-A266-A8BF-960A-4CB56CC6DE6F')
            ->withDepositAmount(10000);

        $response = $handler->updateApplication($application);

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('6985ef52-7d7c-457e-9a03-e98b648bf9b7', $result['data']['id']);
    }
}

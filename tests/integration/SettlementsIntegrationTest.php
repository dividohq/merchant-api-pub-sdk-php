<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;

class SettlementsIntegrationTest extends MerchantSDKTestCase
{
    public function test_GetSettlementsFromClient_ReturnsSettlements()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(
                200,
                [],
                file_get_contents(
                    APP_PATH . '/tests/assets/responses/settlements_page_1.json'
                )
            )
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(1);

        $settlements = $sdk->getSettlementsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $settlements);
        self::assertCount(4, $settlements->getResources());
        self::assertIsObject($settlements->getResources()[0]);
        self::assertObjectHasProperty('id', $settlements->getResources()[0]);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlements->getResources()[0]->id);
    }

    public function test_GetSettlementsByPageFromClient_ReturnsSettlements()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(
                200,
                [],
                file_get_contents(
                    APP_PATH . '/tests/assets/responses/settlements_page_1.json'
                )
            )
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(1);

        $settlements = $sdk->getSettlementsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $settlements);
        self::assertCount(4, $settlements->getResources());
        self::assertIsObject($settlements->getResources()[0]);
        self::assertObjectHasProperty('id', $settlements->getResources()[0]);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlements->getResources()[0]->id);
    }

    public function test_GetAllSettlementsFromClient_ReturnsSettlements()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(
                200,
                [],
                file_get_contents(
                    APP_PATH . '/tests/assets/responses/settlements_page_1.json'
                )
            )
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $settlements = $sdk->getAllSettlements($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $settlements);
        self::assertCount(4, $settlements->getResources());
        self::assertIsObject($settlements->getResources()[0]);
        self::assertObjectHasProperty('id', $settlements->getResources()[0]);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlements->getResources()[0]->id);
    }

    public function test_YieldAllSettlementsFromClient_ReturnsSettlementGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));

        $httpClient->addResponse(
            $this->createResponseMock(
                200,
                [],
                file_get_contents(
                    APP_PATH . '/tests/assets/responses/settlements_page_1.json'
                )
            )
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $settlements = $sdk->yieldAllSettlements($requestOptions);

        self::assertInstanceOf(\Generator::class, $settlements);

        $plan = $settlements->current();
        self::assertCount(4, iterator_to_array($settlements, false));

        self::assertIsObject($plan);
        self::assertObjectHasProperty('id', $plan);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $plan->id);
    }

    public function test_GetSettlementsByPageFromClient_WithSort_ReturnsSortedSettlements()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(
                200,
                [],
                file_get_contents(
                    APP_PATH . '/tests/assets/responses/settlements_page_1.json'
                )
            )
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(1)->setSort('-created_at');

        $sdk->getSettlementsByPage($requestOptions);

        self::addToAssertionCount(1);
    }

    public function test_YieldSettlementsByPageFromClient_ReturnsSettlements()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(
                200,
                [],
                file_get_contents(
                    APP_PATH . '/tests/assets/responses/settlements_page_1.json'
                )
            )
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $settlements = $sdk->yieldSettlementsByPage($requestOptions);

        self::assertInstanceOf(\Generator::class, $settlements);

        $settlement = $settlements->current();
        self::assertCount(4, iterator_to_array($settlements, false));

        self::assertIsObject($settlement);
        self::assertObjectHasProperty('id', $settlement);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlement->id);
    }
}

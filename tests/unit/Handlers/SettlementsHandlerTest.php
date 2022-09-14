<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\Settlements\Handler;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;

class SettlementsHandlerTest extends MerchantSDKTestCase
{
    private $settlementId = '6EC506EE-7919-11E8-A4CE-0242AC1E000B';

    public function test_GetSettlements_ReturnsSettlements()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/settlements_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(1);

        $settlements = $handler->getSettlements($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $settlements);
        self::assertCount(4, $settlements->getResources());
        self::assertIsObject($settlements->getResources()[0]);
        self::assertObjectHasAttribute('id', $settlements->getResources()[0]);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlements->getResources()[0]->id);
    }

    public function test_GetSettlementsByPage_ReturnsSettlements()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/settlements_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(1);

        $settlements = $handler->getSettlementsByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $settlements);
        self::assertCount(4, $settlements->getResources());
        self::assertIsObject($settlements->getResources()[0]);
        self::assertObjectHasAttribute('id', $settlements->getResources()[0]);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlements->getResources()[0]->id);
    }

    public function test_GetAllSettlements_ReturnsSettlements()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/settlements_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $settlements = $handler->getAllSettlements($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $settlements);
        self::assertCount(4, $settlements->getResources());
        self::assertIsObject($settlements->getResources()[0]);
        self::assertObjectHasAttribute('id', $settlements->getResources()[0]);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlements->getResources()[0]->id);
    }

    public function test_YieldAllSettlements_ReturnsSettlementGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/settlements_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $settlements = $handler->yieldAllSettlements($requestOptions);

        self::assertInstanceOf(\Generator::class, $settlements);

        $plan = $settlements->current();
        self::assertCount(4, $settlements);

        self::assertIsObject($plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $plan->id);
    }

    public function test_YieldSettlementsByPage_ReturnsSettlementsGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/settlements_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $settlements = $handler->yieldSettlements($requestOptions);

        self::assertInstanceOf(\Generator::class, $settlements);

        $settlement = $settlements->current();

        // Bug?:
        // Failed asserting that actual size 0 matches expected size 0
        self::assertCount(4, $settlements);

        self::assertIsObject($settlement);
        self::assertObjectHasAttribute('id', $settlement);
        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $settlement->id);
    }

    public function test_GetSettlementsByPage_WithSort_ReturnsSortedSettlements()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/settlements_page_1.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(1)->setSort('-created_at');

        $handler->getSettlementsByPage($requestOptions);

        self::addToAssertionCount(1);
    }

    public function test_GetSingleSettlement_ReturnsSingleSettlement()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/settlements_get_one.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $response = $handler->getSingleSettlement($this->settlementId);

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('6EC506EE-7919-11E8-A4CE-0242AC1E000B', $result['data']['id']);
    }
}

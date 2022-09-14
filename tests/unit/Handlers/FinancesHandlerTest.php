<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Handlers\Finances\Handler;
use Divido\MerchantSDK\Response\ResponseWrapper;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;

class FinancesHandlerTest extends MerchantSDKTestCase
{
    public function test_GetFinances_ReturnsFinances()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/finance_get_plans.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $plans = $handler->getPlans($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $plans);
        self::assertCount(4, $plans->getResources());
        self::assertIsObject($plans->getResources()[0]);
        self::assertObjectHasAttribute('id', $plans->getResources()[0]);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plans->getResources()[0]->id);
    }

    public function test_GetFinancesByPage_ReturnsFinances()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/finance_get_plans.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $plans = $handler->getPlansByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $plans);
        self::assertCount(4, $plans->getResources());
        self::assertIsObject($plans->getResources()[0]);
        self::assertObjectHasAttribute('id', $plans->getResources()[0]);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plans->getResources()[0]->id);
    }

    public function test_GetAllFinances_ReturnsFinances()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/finance_get_plans.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $plans = $handler->getAllPlans($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $plans);
        self::assertCount(4, $plans->getResources());
        self::assertIsObject($plans->getResources()[0]);
        self::assertObjectHasAttribute('id', $plans->getResources()[0]);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plans->getResources()[0]->id);
    }

    public function test_YieldAllFinances_ReturnsFinanceGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/finance_get_plans.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions());

        $plans = $handler->yieldAllPlans($requestOptions);

        self::assertInstanceOf(\Generator::class, $plans);

        $plan = $plans->current();
        self::assertCount(4, $plans);

        self::assertIsObject($plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plan->id);
    }

    public function test_YieldFinancesByPage_ReturnsFinancesGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/finance_get_plans.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(2);

        $plans = $handler->yieldPlans($requestOptions);

        self::assertInstanceOf(\Generator::class, $plans);

        $plan = $plans->current();

        // Bug?:
        // Failed asserting that actual size 0 matches expected size 0
        self::assertCount(4, $plans);

        self::assertIsObject($plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plan->id);
    }

    public function test_GetFinancesByPage_WithSort_ReturnsSortedFinances()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/finance_get_plans.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $requestOptions = (new ApiRequestOptions())->setPage(1)->setSort('-created_at');

        $handler->getPlansByPage($requestOptions);

        self::addToAssertionCount(1);
    }
}

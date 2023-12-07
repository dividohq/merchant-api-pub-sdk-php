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

class FinancesIntegrationTest extends MerchantSDKTestCase
{
    public function test_GetFinancesFromClient_ReturnsFinances()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $plans = $sdk->getPlansByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $plans);
        self::assertCount(4, $plans->getResources());
        self::assertIsObject($plans->getResources()[0]);
        self::assertObjectHasAttribute('id', $plans->getResources()[0]);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plans->getResources()[0]->id);    }

    public function test_GetFinancesByPageFromClient_ReturnsFinances()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(3);

        $plans = $sdk->getPlansByPage($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $plans);
        self::assertCount(4, $plans->getResources());
        self::assertIsObject($plans->getResources()[0]);
        self::assertObjectHasAttribute('id', $plans->getResources()[0]);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plans->getResources()[0]->id);    }

    public function test_GetAllFinancesFromClient_ReturnsFinances()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $plans = $sdk->getAllPlans($requestOptions);

        self::assertInstanceOf(ResponseWrapper::class, $plans);
        self::assertCount(4, $plans->getResources());
        self::assertIsObject($plans->getResources()[0]);
        self::assertObjectHasAttribute('id', $plans->getResources()[0]);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plans->getResources()[0]->id);
    }

    public function test_YieldAllFinancesFromClient_ReturnsFinanceGenerator()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $plans = $sdk->yieldAllPlans($requestOptions);

        self::assertInstanceOf(\Generator::class, $plans);

        $plan = $plans->current();
        self::assertCount(4, $plans);

        self::assertIsObject($plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plan->id);
    }

    public function test_GetFinancesByPageFromClient_WithSort_ReturnsSortedFinances()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json'))
        );
        
        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions())->setPage(1)->setSort('-created_at');

        $sdk->getPlansByPage($requestOptions);

        self::addToAssertionCount(1);
    }

    public function test_YieldPlansByPageFromClient_ReturnsPlans()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/finance_get_plans.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $requestOptions = (new ApiRequestOptions());

        $plans = $sdk->yieldPlansByPage($requestOptions);

        self::assertInstanceOf(\Generator::class, $plans);

        $plan = $plans->current();
        self::assertCount(4, $plans);

        self::assertIsObject($plan);
        self::assertObjectHasAttribute('id', $plan);
        self::assertSame('F7485F0E5-202B-4879-4F00-154E109E7FE4', $plan->id);
    }
}

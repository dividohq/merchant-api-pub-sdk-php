<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;

class PlatformEnvironmentsIntegrationTest extends MerchantSDKTestCase
{
    public function test_YieldApplicationRefundsByPageFromClient_ReturnsApplicationRefunds()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/environment.json'))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $sdk = new Client($wrapper, Environment::SANDBOX);

        $environment = $sdk->platformEnvironments()->getPlatformEnvironment();
        $body = json_decode($environment->getBody()->getContents(), true);

        self::assertSame('divido', $body['data']['environment']);
    }
}

<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\PlatformEnvironments\Handler;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;

class PlatformEnvironmentsHandlerTest extends MerchantSDKTestCase
{
    public function test_GetEnvironment_ReturnsEnvironment()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(200, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/environment.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $response = $handler->getPlatformEnvironment();

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('divido', $result['data']['environment']);
    }
}

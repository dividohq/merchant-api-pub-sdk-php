<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\PlatformEnvironments\Handler;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class PlatformEnvironmentsHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    public function test_GetEnvironment_ReturnsEnvironment()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/environment.json')),
        ], $history);
        $httpClientWrapper = new HttpClientWrapper(new GuzzleAdapter($client), '', '');

        $handler = new Handler($httpClientWrapper);

        $response = $handler->getPlatformEnvironment();

        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        // self::assertSame(
        //     "/applications/{$this->applicationId}/refunds/26d56518-e4a0-4d33-9415-be3c8d6c2661",
        //     $history[0]['request']->getUri()->getPath()
        // );

        $result = json_decode($response->getBody(), true);

        self::assertSame('divido', $result['data']['environment']);
    }
}

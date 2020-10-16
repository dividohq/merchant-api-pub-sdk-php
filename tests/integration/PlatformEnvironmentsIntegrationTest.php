<?php

namespace Divido\MerchantSDK\Test\Integration;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class PlatformEnvironmentsIntegrationTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    public function test_YieldApplicationRefundsByPageFromClient_ReturnsApplicationRefunds()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/environment.json')),
        ], $history);

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($client),
            Environment::CONFIGURATION[Environment::SANDBOX]['base_uri'],
            'test_key'
        );

        $sdk = new Client($httpClientWrapper, Environment::SANDBOX);

        $environment = $sdk->platformEnvironments()->getPlatformEnvironment();
        $body = json_decode($environment->getBody()->getContents(), 1);

        self::assertSame('divido', $body['data']['environment']);
    }
}

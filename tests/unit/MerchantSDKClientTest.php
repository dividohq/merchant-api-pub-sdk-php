<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\GuzzleWrapper;
use Divido\MerchantSDK\Handlers\FinancesHandler;
use Divido\MerchantSDK\Test\Stubs\HttpClient\GuzzleAdapter;
use GuzzleHttp\ClientInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class MerchantSDKClientTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_InstantiationWithoutEnvironment_UsesSandboxEnvironment()
    {
        $mock_Client = \Mockery::spy(\GuzzleHttp\Client::class);

        $httpClient = new GuzzleAdapter($mock_Client);

        $sdk = new Client($httpClient);
        $this->assertSame(Environment::SANDBOX, $sdk->getEnvironment());
    }

    public function test_InstantiationWithEnvironment_UsesPassedEnvironment()
    {
        $mock_Client = \Mockery::spy(\GuzzleHttp\Client::class);

        $httpClient = new GuzzleAdapter($mock_Client);

        $sdk = new Client($httpClient, Environment::PRODUCTION);
        $this->assertSame(Environment::PRODUCTION, $sdk->getEnvironment());
    }
}

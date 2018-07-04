<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\GuzzleWrapper;
use Divido\MerchantSDK\Handlers\FinancesHandler;
use GuzzleHttp\ClientInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class MerchantSDKClientTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_InstantiationWithoutEnvironment_UsesSandboxEnvironment()
    {
        $sdk = new Client('test-api-key');
        $this->assertSame(Environment::SANDBOX, $sdk->getEnvironment());
    }

    public function test_InstantiationWithEnvironment_UsesPassedEnvironment()
    {
        $sdk = new Client('test-api-key', Environment::PRODUCTION);
        $this->assertSame(Environment::PRODUCTION, $sdk->getEnvironment());
    }
}

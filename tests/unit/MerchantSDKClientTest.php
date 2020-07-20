<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class MerchantSDKClientTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_InstantiationWithoutEnvironment_UsesSandboxEnvironment()
    {
        $sdk = new Client("_API_KEY");
        $this->assertSame(Environment::SANDBOX, $sdk->getEnvironment());
    }

    public function test_InstantiationWithEnvironment_UsesPassedEnvironment()
    {
        $sdk = new Client("_API_KEY", Environment::PRODUCTION);
        $this->assertSame(Environment::PRODUCTION, $sdk->getEnvironment());
    }
}

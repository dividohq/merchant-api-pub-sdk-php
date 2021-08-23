<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Environment;

class EnvironmentTest extends MerchantSDKTestCase
{
    /**
     * Test getting the correct environment based on the api key
     *
     * @dataProvider provider_test_GettingEnvironment_WithSuppliedAPIKey_ReturnsCorrectEnvironment
     */
    public function test_GettingEnvironment_WithSuppliedAPIKey_ReturnsCorrectEnvironment($suppliedArgument, $expectedResponse)
    {
        $result = Environment::getEnvironmentFromAPIKey($suppliedArgument);
        $this->assertSame($expectedResponse, $result);
    }

    public function provider_test_GettingEnvironment_WithSuppliedAPIKey_ReturnsCorrectEnvironment()
    {
        return [
            ["dev_f9425ece.3ec313437567c831d289efbdc240dd75'", "dev"],
            ["testing_pk_f9425ece.3ec313437567c831d289efbdc240dd75", "testing"],
            ["staging_pk_f9425ece.3ec313437567c831d289efbdc240dd75", "staging"],
            ["sandbox_f9425ece.3ec313437567c831d289efbdc240dd75", "sandbox"],
            ["user-acceptance-testing_pk_f9425ece.3ec313437567c831d289efbdc240dd75", "uat"],
            ["production_f9425ece.3ec313437567c831d289efbdc240dd75", "production"],
            ["live_pk_f9425ece.3ec313437567c831d289efbdc240dd75", "production"]
        ];
    }
}

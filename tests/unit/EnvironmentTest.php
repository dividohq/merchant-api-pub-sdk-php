<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Environment;

class EnvironmentTest extends MerchantSDKTestCase
{
    /**
     * Test getting the correct environment based on the api key
     *
     */
    public function test_GettingEnvironment_WithSuppliedAPIKey_ReturnsCorrectEnvironment()
    {
        // Dev api keys should be correctly identified
        $apiKey = 'dev_f9425ece.3ec313437567c831d289efbdc240dd75';
        $result = Environment::getEnvironmentFromAPIKey($apiKey);
        $this->assertSame("dev", $result);

        // Testing api keys should be correctly identified
        $apiKey = 'testing_pk_f9425ece.3ec313437567c831d289efbdc240dd75';
        $result = Environment::getEnvironmentFromAPIKey($apiKey);
        $this->assertSame("testing", $result);

        // Staging api keys should be correctly identified
        $apiKey = 'staging_pk_f9425ece.3ec313437567c831d289efbdc240dd75';
        $result = Environment::getEnvironmentFromAPIKey($apiKey);
        $this->assertSame("staging", $result);

        // Sandbox api keys should be correctly identified
        $apiKey = 'sandbox_f9425ece.3ec313437567c831d289efbdc240dd75';
        $result = Environment::getEnvironmentFromAPIKey($apiKey);
        $this->assertSame("sandbox", $result);

        // UAT api keys should be correctly identified
        $apiKey = 'user-acceptance-testing_pk_f9425ece.3ec313437567c831d289efbdc240dd75';
        $result = Environment::getEnvironmentFromAPIKey($apiKey);
        $this->assertSame("user-acceptance-testing", $result);

        // Production api keys should be correctly identified
        $apiKey = 'production_f9425ece.3ec313437567c831d289efbdc240dd75';
        $result = Environment::getEnvironmentFromAPIKey($apiKey);
        $this->assertSame("production", $result);

        // Live api keys should be correctly identified
        $apiKey = 'live_pk_f9425ece.3ec313437567c831d289efbdc240dd75';
        $result = Environment::getEnvironmentFromAPIKey($apiKey);
        $this->assertSame("production", $result);
    }
}
<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Exceptions\InvalidApiKeyFormatException;
use Divido\MerchantSDK\Exceptions\InvalidEnvironmentException;

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

    public function provider_test_shouldThrowAnErrorIfApiDoesNotMatchValidEnvironment(): array
    {
        return [
            'key_with_env_prefix_that_does_not_exist_as_constant' => [
                uniqid('doesnotexistasconstant_'),
                InvalidEnvironmentException::class
            ],
            'key_without_underscore' => [
                uniqid('apikey'),
                InvalidApiKeyFormatException::class
            ],
            'empty_key' => [
                '',
                InvalidApiKeyFormatException::class
            ],
            'key_that_starts_with_underscore' => [
                uniqid('_apikey_'),
                InvalidApiKeyFormatException::class
            ],
            'cheeky_user_tried_using_configuration_as_key_may_be_to_try_to_break_things' => [
                uniqid('configuration_'),
                InvalidEnvironmentException::class
            ],
            'null_key' => [
                null,
                InvalidApiKeyFormatException::class
            ],
        ];
    }

    /**
     * @dataProvider provider_test_shouldThrowAnErrorIfApiDoesNotMatchValidEnvironment
     * @param mixed $apiKey
     * @param $expectedException
     */
    public function test_shouldThrowAnErrorIfApiDoesNotMatchValidEnvironment(
        $apiKey,
        $expectedException
    )
    {
        $this->expectException($expectedException);

        Environment::getEnvironmentFromAPIKey($apiKey);
    }
}

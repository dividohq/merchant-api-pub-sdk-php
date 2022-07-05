<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Exceptions\InvalidConfigurationPropertyNameException;
use Divido\MerchantSDK\Exceptions\InvalidEnvironmentException;

class EnvironmentConfigurationTest extends MerchantSDKTestCase
{
    public function data_provider_shouldBeAbleToGetConfigurationAllPropertiesFromAllEnvironments()
    {
        $properties = [];
        $environments = [];

        foreach (Environment::CONFIGURATION as $environment => $config) {
            $environments[] = $environment;
            $properties = array_merge($properties, array_keys($config));
        }

        $testItems = [];
        foreach ($environments as $environment) {
            foreach ($properties as $configPropertyName) {
                $testItems['getting_config_' . $environment . '_' . $configPropertyName] = [
                    $environment,
                    $configPropertyName
                ];
            }
        }

        return $testItems;
    }

    /**
     * Test that all properties exists on all environments
     *
     * @dataProvider data_provider_shouldBeAbleToGetConfigurationAllPropertiesFromAllEnvironments
     */
    public function test_shouldBeAbleToGetConfigurationAllPropertiesFromAllEnvironments($environment, $configPropertyName)
    {
        $this->assertNotEmpty(
            Environment::getConfigurationForMultiTenantEnvironment($environment, $configPropertyName)
        );
    }

    public function test_shouldThrowExceptionIfEnvironmentIsWrong()
    {
        $this->expectException(InvalidEnvironmentException::class);

        Environment::getConfigurationForMultiTenantEnvironment(uniqid('bleh'));
    }

    public function test_shouldReturnEntireConfigurationForEnvironment()
    {
        self::assertSame(
            Environment::CONFIGURATION[Environment::DEV],
            Environment::getConfigurationForMultiTenantEnvironment(Environment::DEV)
        );
    }

    public function test_shouldBeAbleToGetConfigurationProperty()
    {
        self::assertSame(
            Environment::CONFIGURATION[Environment::DEV][Environment::CONFIGURATION_PROPERTY_BASE_URI],
            Environment::getConfigurationForMultiTenantEnvironment(Environment::DEV, Environment::CONFIGURATION_PROPERTY_BASE_URI)
        );
    }

    public function test_shouldThrowExceptionIfConfigurationPropertyDoesNotExist()
    {
        $this->expectException(InvalidConfigurationPropertyNameException::class);
        Environment::getConfigurationForMultiTenantEnvironment(Environment::DEV, uniqid('property_that_does_not_exist_'));
    }
}

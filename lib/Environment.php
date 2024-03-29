<?php

declare(strict_types=1);

namespace Divido\MerchantSDK;

use Divido\MerchantSDK\Exceptions\InvalidApiKeyFormatException;
use Divido\MerchantSDK\Exceptions\InvalidConfigurationPropertyNameException;
use Divido\MerchantSDK\Exceptions\InvalidEnvironmentException;

/**
 * Class Environment
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 */
class Environment
{
    public const DEV = "dev";

    public const TESTING = "testing";

    public const SANDBOX = "sandbox";

    public const STAGING = "staging";

    public const USER_ACCEPTANCE_TESTING = "uat";

    public const PRODUCTION = "production";

    public const LIVE = "production";

    public const CONFIGURATION_PROPERTY_BASE_URI = 'base_uri';

    public const CONFIGURATION = [
        'dev' => [
            self::CONFIGURATION_PROPERTY_BASE_URI => 'https://merchant-api-pub.api.dev.divido.net',
        ],
        'testing' => [
            self::CONFIGURATION_PROPERTY_BASE_URI => 'https://merchant-api.api.testing.divido.net',
        ],
        'sandbox' => [
            self::CONFIGURATION_PROPERTY_BASE_URI => 'https://merchant.api.sandbox.divido.net',
        ],
        'staging' => [
            self::CONFIGURATION_PROPERTY_BASE_URI => 'https://merchant-api.api.staging.divido.net',
        ],
        'production' => [
            self::CONFIGURATION_PROPERTY_BASE_URI => 'https://merchant.api.divido.com',
        ],
    ];

    /**
     * @param $apiKey
     * @return true
     * @throws InvalidApiKeyFormatException
     */
    public static function validateApiKeyFormat($apiKey)
    {
        if(empty($apiKey)) {
            throw new InvalidApiKeyFormatException('API Key can not be empty');
        }

        if(strpos($apiKey, '_') === false) {
            throw new InvalidApiKeyFormatException('API key does not contain an underscore');
        }

        if(strpos($apiKey, '_') === 0) {
            throw new InvalidApiKeyFormatException('API key can not start with an underscore');
        }

        return true;
    }

    /**
     * Get the environment based off of the provided API key
     *
     *
     * @param string $apiKey The API key to get the environment from
     *
     * @return string The environment corresponding to the API key
     *
     * @throws InvalidEnvironmentException|InvalidApiKeyFormatException
     */
    public static function getEnvironmentFromAPIKey($apiKey)
    {
        // Validate the formatting
        self::validateApiKeyFormat($apiKey);

        // Get the environment name part of they api key
        $splitApiKey = explode('_', $apiKey);
        $environment = str_replace('-', '_', strtoupper($splitApiKey[0]));

        // Get the full name of the constant (something like self::DEV, self::TESTING etc)
        $constantName = ('LIVE' === $environment)
            ? 'self::PRODUCTION'
            : 'self::'. $environment;

        // Check that the constant is defined.
        if(!defined($constantName)) {
            throw new InvalidEnvironmentException('Could not find environment with name: ' . $environment);
        }

        // Get the value of the constant
        $constantValue = constant($constantName);

        // If the accessed constant is not a string
        if(!is_string($constantValue)) {
            throw new InvalidEnvironmentException('Could not find valid environment value for environment name: ' . $environment);
        }

        return $constantValue;
    }

    /**
     * Get the configuration array for multi tenant environment
     * If 'propertyName' is supplied will try to get that property and return only that from the configuration array
     *
     * @param string $environmentName
     * @param string|null $propertyName
     * @return mixed
     * @throws InvalidEnvironmentException
     * @throws InvalidConfigurationPropertyNameException
     */
    public static function getConfigurationForMultiTenantEnvironment($environmentName, $propertyName = null)
    {
        // Check that multi tenant configuration array has environment as key
        if (!array_key_exists($environmentName, self::CONFIGURATION)) {
            throw new InvalidEnvironmentException('Could not find configuration for environment');
        }

        // Get the multi tenant configuration array for this environment
        $environmentConfiguration = self::CONFIGURATION[$environmentName];

        // If no property is supplied, return the entire configuration array as is
        if($propertyName === null) {
            return $environmentConfiguration;
        }

        // Property does not exist
        if(!array_key_exists($propertyName, $environmentConfiguration)) {
            throw new InvalidConfigurationPropertyNameException('Could not find configuration property');
        }

        // Return just the requested property
        return $environmentConfiguration[$propertyName];
    }
}

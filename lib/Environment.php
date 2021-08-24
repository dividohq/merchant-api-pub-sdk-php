<?php

namespace Divido\MerchantSDK;

use Divido\MerchantSDK\Exceptions\InvalidApiKeyFormatException;
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
    const DEV = "dev";

    const TESTING = "testing";

    const SANDBOX = "sandbox";

    const STAGING = "staging";

    const USER_ACCEPTANCE_TESTING = "uat";

    const PRODUCTION = "production";

    const LIVE = "production";

    const CONFIGURATION = [
        'dev' => [
            'base_uri' => 'https://merchant-api-pub.api.dev.divido.net',
        ],
        'testing' => [
            'base_uri' => 'https://merchant-api-pub.api.testing.divido.net',
        ],
        'sandbox' => [
            'base_uri' => 'https://merchant.api.sandbox.divido.net',
        ],
        'staging' => [
            'base_uri' => 'https://merchant-api-pub.api.staging.divido.net',
        ],
        'production' => [
            'base_uri' => 'https://merchant.api.divido.com',
        ],
    ];

    /**
     * @param $apiKey
     * @return true
     * @throws InvalidApiKeyFormatException
     */
    public static function validateApiKeyFormat($apiKey)
    {
        if(empty($apiKey)){
            throw new InvalidApiKeyFormatException('API Key can not be empty');
        }

        if(strpos($apiKey, '_') === false){
            throw new InvalidApiKeyFormatException('API key does not contain an underscore');
        }

        if(strpos($apiKey, '_') === 0){
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
        $environment = str_replace('-','_',strtoupper($splitApiKey[0]));

        // Get the full name of the constant (something like self::DEV, self::TESTING etc)
        $constantName = ('LIVE' === $environment)
            ? 'self::PRODUCTION'
            : 'self::'. $environment;

        // Check that the constant is defined.
        if(!defined($constantName)){
            throw new InvalidEnvironmentException('Could not find environment with name: ' . $environment);
        }

        // Get the value of the constant
        $constantValue = constant($constantName);

        // If the accessed constant is not a string
        if(!is_string($constantValue)){
            throw new InvalidEnvironmentException('Could not find valid environment value for environment name: ' . $environment);
        }

        return $constantValue;
    }
}

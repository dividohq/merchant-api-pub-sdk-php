<?php

namespace Divido\MerchantSDK;

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

    const USER_ACCEPTANCE_TESTING = "user-acceptance-testing";

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
     * Get the environment based off of the provided API key
     *
     *
     * @param string $apiKey The API key to get the environment from
     *
     * @return string The environment corresponding to the API key
     */
    public static function getEnvironmentFromAPIKey($apiKey)
    {
        $splitApiKey = explode('_', $apiKey);
        $environment = str_replace('-','_',strtoupper($splitApiKey[0]));
        return ('LIVE' == $environment)
            ? constant('self::PRODUCTION')
            : constant('self::'. $environment);
    }
}

<?php

namespace Divido\MerchantSDK;

/**
 * Class Environment
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class Environment
{
    const DEV = "dev";

    const TESTING = "testing";

    const SANDBOX = "sandbox";

    const STAGING = "staging";

    const PRODUCTION = "production";

    const CONFIGURATION = [
        'dev' => [
            'base_uri' => 'https://merchant-api.api.dev.divido.net',
        ],
        'testing' => [
            'base_uri' => 'https://merchant.api.testing.divido.net',
        ],
        'sandbox' => [
            'base_uri' => 'https://merchant.api.sandbox.divido.net',
        ],
        'staging' => [
            'base_uri' => 'https://merchant.api.staging.divido.net',
        ],
        'production' => [
            'base_uri' => 'https://merchant.api.divido.com',
        ],
    ];
}

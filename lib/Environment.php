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
    const SANDBOX = "sandbox";

    const PRODUCTION = "production";

    const CONFIGURATION = [
        'sandbox' => [
            'base_uri' => 'https://merchant.api.testing.divido.net',
        ],
        'production' => [
            'base_uri' => 'https://foo.com',
        ],
    ];
}

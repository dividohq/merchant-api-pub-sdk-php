<?php

namespace Divido\MerchantSDK\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class GuzzleHelper
{
    /**
     * Get the major version of guzzle that is installed.
     *
     * @internal This function is internal and should not be used outside aws/aws-sdk-php.
     * @return int
     * @throws \RuntimeException
     */
    public static function getGuzzleMajorVersion()
    {
        static $cache = null;
        if (null !== $cache) {
            return $cache;
        }

        if (defined('\GuzzleHttp\ClientInterface::VERSION')) {
            $version = (string) ClientInterface::VERSION;
            if ($version[0] === '6') {
                return $cache = 6;
            }
            if ($version[0] === '5') {
                return $cache = 5;
            }
        } elseif (method_exists(Client::class, 'sendRequest')) {
            return $cache = 7;
        }

        throw new \RuntimeException('Unable to determine what Guzzle version is installed.');
    }

    /**
     * Creates a default HTTP handler based on the available clients.
     *
     * @return callable
     */
    public static function getDefaultHttpHandler($client = null)
    {
        if (is_null($client)) {
            $client = new Client();
        }

        $version = self::getGuzzleMajorVersion();

        // If Guzzle 6 or 7 installed
        if ($version === 6 || $version === 7) {
            return new \Divido\MerchantSDK\HttpClient\GuzzleV6\GuzzleAdapter($client);
        }

        // If Guzzle 5 installed
        if ($version === 5) {
            return new \Divido\MerchantSDK\HttpClient\GuzzleV5\GuzzleAdapter($client);
        }

        throw new \RuntimeException('Unknown Guzzle version: ' . $version);
    }
}

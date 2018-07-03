<?php

namespace Divido\MerchantSDK\Test\Unit;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\TestCase;

class MerchantSDKTestCase extends TestCase
{
    /**
     * @param array $calls
     * @param array $history
     * @return \GuzzleHttp\Client
     */
    protected function getGuzzleStackedClient($calls = [], &$history = [])
    {
        $mockHandler = new MockHandler($calls);
        $historyHandler = Middleware::history($history);


        $stack = HandlerStack::create($mockHandler);
        $stack->push($historyHandler);

        return new \GuzzleHttp\Client(['handler' => $stack,]);
    }
}

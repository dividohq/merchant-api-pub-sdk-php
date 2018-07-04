<?php

namespace Divido\MerchantSDK\Test\Unit\Helpers;

use Divido\MerchantSDK\Helpers\Str;
use Divido\MerchantSDK\Test\Unit\MerchantSDKTestCase;

class SettlementsHandlerTest extends MerchantSDKTestCase
{
    /**
     * Test getting lower case string with supplied argument returns lower case string.
     *
     * @dataProvider provider_test_GettingLowerCaseString_WithSuppliedArgument_ReturnsLowerCaseString
     */
    public function test_GettingLowerCaseString_WithSuppliedArgument_ReturnsLowerCaseString($suppliedArgument, $expectedResponse)
    {
        $result = Str::lower($suppliedArgument);

        $this->assertSame($expectedResponse, $result);
    }

    public function provider_test_GettingLowerCaseString_WithSuppliedArgument_ReturnsLowerCaseString()
    {
        return [
            ["Foo", "foo"],
            ["camelCaseFoo", "camelcasefoo"],
            ["separate WORDS", "separate words"],
            ["", ""],
            [null, ""],
            [1, "1"],
        ];
    }

    /**
     * Test getting snake case string with supplied argument returns snake case string.
     *
     * @dataProvider provider_test_GettingSnakeCaseString_WithSuppliedArgument_ReturnsSnakeCaseString
     */
    public function test_GettingSnakeCaseString_WithSuppliedArgument_ReturnsSnakeCaseString($suppliedArgument, $expectedResponse)
    {
        $result = Str::snake($suppliedArgument);

        $this->assertSame($expectedResponse, $result);
    }

    public function provider_test_GettingSnakeCaseString_WithSuppliedArgument_ReturnsSnakeCaseString()
    {
        return [
            ["Foo", "foo"],
            ["camelCaseFoo", "camel_case_foo"],
            ["camel case foo", "camel_case_foo"],
            ["", ""],
            [null, ""],
            [1, "1"],
        ];
    }
}

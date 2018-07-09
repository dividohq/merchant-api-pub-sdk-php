<?php

namespace Divido\MerchantSDK\Models;

use Divido\MerchantSDK\Helpers\Str;

/**
 * Class AbstractModel
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
abstract class AbstractModel
{
    /**
     * Get payload as json encoded string.
     *
     * @return string
     */
    public function getJsonPayload()
    {
        return json_encode($this->getPayload());
    }

    /**
     * Get payload from model attributes.
     *
     * @return array
     */
    public function getPayload()
    {
        $payload = array_filter(get_object_vars($this), function ($value) {
            return !is_null($value);
        });

        return $this->transformKeysToSnakeCase($payload);
    }

    /**
     * Convert each array key to snake case.
     *
     * @param string $input
     * @return array
     */
    private function transformKeysToSnakeCase($input)
    {
        $array = [];

        foreach ($input as $key => $value) {
            $array[Str::snake($key)] = $value;
        }

        return $array;
    }
}

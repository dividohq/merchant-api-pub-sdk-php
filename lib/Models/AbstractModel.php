<?php

namespace Divido\MerchantSDK\Models;

abstract class AbstractModel
{
    public function getJsonPayload()
    {
        return json_encode($this->getPayload());
    }

    public function getPayload()
    {
        // @todo - snake_case attributes.
        return array_filter(get_object_vars($this), function ($value) {
            return !is_null($value);
        });
    }
}

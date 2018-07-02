<?php

namespace Divido\MerchantSDK\Models;

class Settlement extends AbstractModel
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function withId($id)
    {
        $cloned = clone $this;

        $cloned->id = $id;

        return $cloned;
    }
}

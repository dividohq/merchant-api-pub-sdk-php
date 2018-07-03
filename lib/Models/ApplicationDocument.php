<?php

namespace Divido\MerchantSDK\Models;

class ApplicationDocument extends AbstractModel
{
    protected $document;

    public function withDocument($document)
    {
        $cloned = clone $this;

        $cloned->document = $document;

        return $cloned;
    }
}

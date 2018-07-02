<?php

namespace Divido\MerchantSDK\Models;

class ApplicationDocument extends AbstractModel
{
    protected $id;

    protected $document;

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

    public function withDocument($document)
    {
        $cloned = clone $this;

        $cloned->document = $document;

        return $cloned;
    }
}

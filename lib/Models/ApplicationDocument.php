<?php

namespace Divido\MerchantSDK\Models;

/**
 * Class ApplicationDocument
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ApplicationDocument extends AbstractModel
{
    /**
     * @var string
     */
    protected $document;

    /**
     * With document.
     *
     * @param string $document
     *
     * @return \Divido\MerchantSDK\Models\ApplicationDocument
     */
    public function withDocument($document)
    {
        $cloned = clone $this;

        $cloned->document = $document;

        return $cloned;
    }
}

<?php

namespace Divido\MerchantSDK\Exceptions;

use Divido\ErrorExceptions\AbstractException;

/**
 * Class MerchantApiResponseException
 *
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2017, Divido
 * @package Divido\MerchantSDK
 */
class MerchantApiResponseException extends AbstractException
{
    final function __construct(string $title, int $status, array $meta)
    {
        parent::__construct(
            $title,
            $status,
            $meta
        );
    }
}

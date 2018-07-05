<?php

namespace Divido\MerchantSDK\Models;

/**
 * Class ApplicationRefund
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ApplicationRefund extends AbstractModel
{
    /**
     * @var string
     */
    protected $amount;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var array
     */
    protected $orderItems = [];

    /**
     * @var string
     */
    protected $deliveryMethod;

    /**
     * @var string
     */
    protected $trackingNumber;

    /**
     * With amount.
     *
     * @param string $amount
     *
     * @return \Divido\MerchantSDK\Models\ApplicationRefund
     */
    public function withAmount($amount)
    {
        $cloned = clone $this;

        $cloned->amount = $amount;

        return $cloned;
    }

    /**
     * With reference.
     *
     * @param string $reference
     *
     * @return \Divido\MerchantSDK\Models\ApplicationRefund
     */
    public function withReference($reference)
    {
        $cloned = clone $this;

        $cloned->reference = $reference;

        return $cloned;
    }

    /**
     * With comment.
     *
     * @param string $comment
     *
     * @return \Divido\MerchantSDK\Models\ApplicationRefund
     */
    public function withComment($comment)
    {
        $cloned = clone $this;

        $cloned->comment = $comment;

        return $cloned;
    }

    /**
     * With order items.
     *
     * @param string $orderItems
     *
     * @return \Divido\MerchantSDK\Models\ApplicationRefund
     */
    public function withOrderItems($orderItems)
    {
        $cloned = clone $this;

        $cloned->orderItems = $orderItems;

        return $cloned;
    }

    /**
     * With delivery method.
     *
     * @param string $deliveryMethod
     *
     * @return \Divido\MerchantSDK\Models\ApplicationRefund
     */
    public function withDeliveryMethod($deliveryMethod)
    {
        $cloned = clone $this;

        $cloned->deliveryMethod = $deliveryMethod;

        return $cloned;
    }

    /**
     * With tracking number.
     *
     * @param string $trackingNumber
     *
     * @return \Divido\MerchantSDK\Models\ApplicationRefund
     */
    public function withTrackingNumber($trackingNumber)
    {
        $cloned = clone $this;

        $cloned->trackingNumber = $trackingNumber;

        return $cloned;
    }
}

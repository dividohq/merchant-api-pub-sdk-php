<?php

namespace Divido\MerchantSDK\Models;

class ApplicationRefund extends AbstractModel
{
    protected $amount;

    protected $reference;

    protected $comment;

    protected $orderItems = [];

    protected $deliveryMethod;

    protected $trackingNumber;

    public function withAmount($amount)
    {
        $cloned = clone $this;

        $cloned->amount = $amount;

        return $cloned;
    }

    public function withReference($reference)
    {
        $cloned = clone $this;

        $cloned->reference = $reference;

        return $cloned;
    }

    public function withComment($comment)
    {
        $cloned = clone $this;

        $cloned->comment = $comment;

        return $cloned;
    }

    public function withOrderItems($orderItems)
    {
        $cloned = clone $this;

        $cloned->orderItems = $orderItems;

        return $cloned;
    }

    public function withDeliveryMethod($deliveryMethod)
    {
        $cloned = clone $this;

        $cloned->deliveryMethod = $deliveryMethod;

        return $cloned;
    }

    public function withTrackingNumber($trackingNumber)
    {
        $cloned = clone $this;

        $cloned->trackingNumber = $trackingNumber;

        return $cloned;
    }
}

<?php

declare(strict_types=1);

namespace Divido\MerchantSDK\Models;

/**
 * Class ApplicationRefund
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 */
class ApplicationRefund extends AbstractModel
{
    /**
     * @var int
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
    protected $reason;

    /**
     * With amount.
     *
     * @param int $amount
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
     * @param array $orderItems
     *
     * @return \Divido\MerchantSDK\Models\ApplicationRefund
     */
    public function withOrderItems(array $orderItems)
    {
        $cloned = clone $this;

        $cloned->orderItems = $orderItems;

        return $cloned;
    }

    /**
     * With reason.
     *
     * @param string $reason
     *
     * @return \Divido\MerchantSDK\Models\ApplicationRefund
     */
    public function withReason($reason)
    {
        $cloned = clone $this;

        $cloned->reason = $reason;

        return $cloned;
    }
}

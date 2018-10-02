<?php

namespace Divido\MerchantSDK\Models;

/**
 * Class Application
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class Application extends AbstractModel
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $merchantChannelId;

    /**
     * @var string
     */
    protected $financePlanId;

    /**
     * @var string
     */
    protected $countryId;

    /**
     * @var string
     */
    protected $currencyId;

    /**
     * @var string
     */
    protected $languageId;

    /**
     * @var array
     */
    protected $applicants = [];

    /**
     * @var array
     */
    protected $orderItems = [];

    /**
     * @var string
     */
    protected $depositAmount;

    /**
     * @var string
     */
    protected $depositPercentage;

    /**
     * @var array
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $finalisationRequired;

    /**
     * @var string
     */
    protected $merchantReference;

    /**
     * @var array
     */
    protected $urls;

    /**
     * Wet id.
     *
     * @param string $(
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * With id.
     *
     * @param string $id
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withId($id)
    {
        $cloned = clone $this;

        $cloned->id = $id;

        return $cloned;
    }

    /**
     * With merchant channel id.
     *
     * @param string $merchantChannelId
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withMerchantChannelId($merchantChannelId)
    {
        $cloned = clone $this;

        $cloned->merchantChannelId = $merchantChannelId;

        return $cloned;
    }

    /**
     * With finance plan id.
     *
     * @param string $financePlanId
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withFinancePlanId($financePlanId)
    {
        $cloned = clone $this;

        $cloned->financePlanId = $financePlanId;

        return $cloned;
    }

    /**
     * With country id.
     *
     * @param string $countryId
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withCountryId($countryId)
    {
        $cloned = clone $this;

        $cloned->countryId = $countryId;

        return $cloned;
    }

    /**
     * With currency id.
     *
     * @param string $currencyId
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withCurrencyId($currencyId)
    {
        $cloned = clone $this;

        $cloned->currencyId = $currencyId;

        return $cloned;
    }

    /**
     * With language id.
     *
     * @param string $languageId
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withLanguageId($languageId)
    {
        $cloned = clone $this;

        $cloned->languageId = $languageId;

        return $cloned;
    }

    /**
     * With applicants.
     *
     * @param array $applicants
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withApplicants(array $applicants)
    {
        $cloned = clone $this;

        $cloned->applicants = $applicants;

        return $cloned;
    }

    /**
     * With order items.
     *
     * @param array $orderItems
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withOrderItems(array $orderItems)
    {
        $cloned = clone $this;

        $cloned->orderItems = $orderItems;

        return $cloned;
    }

    /**
     * With deposit amount.
     *
     * @param int $depositAmount
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withDepositAmount($depositAmount)
    {
        $cloned = clone $this;

        $cloned->depositAmount = $depositAmount;

        return $cloned;
    }

    /**
     * With deposit percentage.
     *
     * @param float $depositPercentage
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withDepositPercentage($depositPercentage)
    {
        $cloned = clone $this;

        $cloned->depositPercentage = $depositPercentage;

        return $cloned;
    }

    /**
     * With metadata.
     *
     * @param array $metadata
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withMetadata(array $metadata)
    {
        $cloned = clone $this;

        $cloned->metadata = $metadata;

        return $cloned;
    }

    /**
     * With finalisation required.
     *
     * @param bool $finalisationRequired
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withFinalisationRequired($finalisationRequired)
    {
        $cloned = clone $this;

        $cloned->finalisationRequired = $finalisationRequired;

        return $cloned;
    }

    /**
     * With merchant reference.
     *
     * @param string $merchantReference
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withMerchantReference($merchantReference)
    {
        $cloned = clone $this;

        $cloned->merchantReference = $merchantReference;

        return $cloned;
    }

    /**
     *
     * @param string $urls
     *
     * @return \Divido\MerchantSDK\Models\Application
     */
    public function withUrls(array $urls)
    {
        $cloned = clone $this;

        $cloned->urls = $urls;

        return $cloned;
    }
}

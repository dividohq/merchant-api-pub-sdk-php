<?php

namespace Divido\MerchantSDK\Models;

class Application extends AbstractModel
{
    protected $id;

    protected $merchantChannelId;

    protected $financePlanId;

    protected $countryId;

    protected $currencyId;

    protected $languageId;

    protected $applicants = [];

    protected $orderItems = [];

    protected $depositAmount;

    protected $depositPercentage;

    protected $metadata;

    protected $finalisationRequired;

    protected $merchantReference;

    protected $merchantRedirectUrl;

    protected $merchantCheckoutUrl;

    protected $merchantResponseUrl;

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

    public function withMerchantChannelId($merchantChannelId)
    {
        $cloned = clone $this;

        $cloned->merchantChannelId = $merchantChannelId;

        return $cloned;
    }

    public function withFinancePlanId($financePlanId)
    {
        $cloned = clone $this;

        $cloned->financePlanId = $financePlanId;

        return $cloned;
    }

    public function withCountryId($countryId)
    {
        $cloned = clone $this;

        $cloned->countryId = $countryId;

        return $cloned;
    }

    public function withCurrencyId($currencyId)
    {
        $cloned = clone $this;

        $cloned->currencyId = $currencyId;

        return $cloned;
    }

    public function withLanguageId($languageId)
    {
        $cloned = clone $this;

        $cloned->languageId = $languageId;

        return $cloned;
    }

    public function withApplicants($applicants)
    {
        $cloned = clone $this;

        $cloned->applicants = $applicants;

        return $cloned;
    }

    public function withOrderItems($orderItems)
    {
        $cloned = clone $this;

        $cloned->orderItems = $orderItems;

        return $cloned;
    }

    public function withDepositAmount($depositAmount)
    {
        $cloned = clone $this;

        $cloned->depositAmount = $depositAmount;

        return $cloned;
    }

    public function withDepositPercentage($depositPercentage)
    {
        $cloned = clone $this;

        $cloned->depositPercentage = $depositPercentage;

        return $cloned;
    }

    public function withMetadata($metadata)
    {
        $cloned = clone $this;

        $cloned->metadata = $metadata;

        return $cloned;
    }

    public function withFinalisationRequired($finalisationRequired)
    {
        $cloned = clone $this;

        $cloned->finalisationRequired = $finalisationRequired;

        return $cloned;
    }

    public function withMerchantReference($merchantReference)
    {
        $cloned = clone $this;

        $cloned->merchantReference = $merchantReference;

        return $cloned;
    }

    public function withMerchantRedirectUrl($merchantRedirectUrl)
    {
        $cloned = clone $this;

        $cloned->merchantRedirectUrl = $merchantRedirectUrl;

        return $cloned;
    }

    public function withMerchantCheckoutUrl($merchantCheckoutUrl)
    {
        $cloned = clone $this;

        $cloned->merchantCheckoutUrl = $merchantCheckoutUrl;

        return $cloned;
    }

    public function withMerchantResponseUrl($merchantResponseUrl)
    {
        $cloned = clone $this;

        $cloned->merchantResponseUrl = $merchantResponseUrl;

        return $cloned;
    }
}

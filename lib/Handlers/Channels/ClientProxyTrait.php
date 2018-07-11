<?php

namespace Divido\MerchantSDK\Handlers\Channels;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * Trait ClientProxyTrait
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
trait ClientProxyTrait
{
    /**
     * @return array
     */
    abstract protected function getHandlers();

    /**
     * @return Handler
     */
    abstract protected function setHandler($key, $value);

    /**
     * Connect to the channels handler.
     *
     * @return Handler
     */
    public function channels()
    {
        if (!array_key_exists('', $this->getHandlers())) {
            $this->setHandler('channels', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['channels'];
    }

    /**
     * Get channels by page.
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getChannelsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        return $this->channels()->getChannels($options);
    }

    /**
     * Get all channels.
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function getAllChannels(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        return $this->channels()->getChannels($options);
    }

    /**
     * Yield all channels.
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function yieldAllChannels(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->channels()->yieldChannels($options) as $channel) {
            yield $channel;
        }
    }

    /**
     * Yield channels by page.
     *
     * @param ApiRequestOptions $options
     * @return ResponseWrapper
     */
    public function yieldChannelsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->channels()->yieldChannels($options) as $channel) {
            yield $channel;
        }
    }
}

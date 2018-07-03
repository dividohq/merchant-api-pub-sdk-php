<?php

namespace Divido\MerchantSDK\Handlers\Channels;

use Divido\MerchantSDK\Handlers\ApiRequestOptions;

/**
 * ClientProxyTrait Client
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
    abstract protected function setHandler(string $key,$value);

    /**
     * @return Handler
     */
    public function channels()
    {
        if (!array_key_exists('', $this->getHandlers())) {
            $this->setHandler('channels', new Handler($this->httpClientWrapper));
        }

        return $this->getHandlers()['channels'];
    }

    function getChannelsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        return $this->channels()->getChannels($options);
    }

    function getAllChannels(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        return $this->channels()->getChannels($options);
    }

    function yieldAllChannels(ApiRequestOptions $options)
    {
        $options->setPaginated(false);
        foreach ($this->channels()->yieldChannels($options) as $channel) {
            yield $channel;
        }
    }

    function yieldChannelsByPage(ApiRequestOptions $options)
    {
        $options->setPaginated(true);
        foreach ($this->channels()->yieldChannels($options) as $channel) {
            yield $channel;
        }
    }
}

<?php

namespace Divido\MerchantSDK;

use Divido\MerchantSDK\Wrappers\WrapperInterface;

/**
 * Class Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 */
class Client
{
    use Handlers\Applications\ClientProxyTrait;
    use Handlers\ApplicationActivations\ClientProxyTrait;
    use Handlers\ApplicationCancellations\ClientProxyTrait;
    use Handlers\ApplicationRefunds\ClientProxyTrait;
    use Handlers\Channels\ClientProxyTrait;
    use Handlers\Finances\ClientProxyTrait;
    use Handlers\PlatformEnvironments\ClientProxyTrait;
    use Handlers\Settlements\ClientProxyTrait;
    use Handlers\Health\ClientProxyTrait;

    /**
     * The API environment to consume
     *
     * @var string
     */
    private $environment;

    /**
     * The endpoint handlers
     *
     * @var array
     */
    private $handlers = [];

    /**
     * @var WrapperInterface $wrapper
     */
    private $wrapper;

    /**
     * @param WrapperInterface $wrapper
     * @param string $environment
     */
    final public function __construct(WrapperInterface $wrapper, $environment = Environment::SANDBOX)
    {
        $this->wrapper = $wrapper;
        $this->environment = $environment;
    }

    /**
     * Get the API environment in use
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Get all the handlers as an array.
     *
     * @return array
     */
    protected function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * Set a defined handler.
     *
     */
    protected function setHandler($key, $value)
    {
        $this->handlers[$key] = $value;
    }
}

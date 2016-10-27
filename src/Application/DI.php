<?php

namespace Ant\Application;

use Pimple\Container;

class DI
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->container = new Container();
        $this->container['config'] = $config;
        if (!empty($config['di'])) {
            foreach ($config['di'] as $key => $conf) {
                $this->container[$key] = $conf;
            }
        }
    }

    /**
     * @param string $key
     *
     * @return mixed
     * @throws \Exception
     */
    public function getService($key)
    {
        if (empty($this->container[$key])) {
            throw new \Exception("Try to get incorrect DI container with key as '{$key}'");
        }

        return $this->container[$key];
    }
}
<?php

namespace Ant\Application;

use Pimple\Container;

class DI
{
    const CONTAINER_CONFIG = 'config';

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

        $this->container[self::CONTAINER_CONFIG] = $config;
        if (!empty($config[Config::DI])) {
            foreach ($config[Config::DI] as $key => $conf) {
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
    public function getContainer($key)
    {
        if (empty($this->container[$key])) {
            throw new \Exception("Try to get incorrect DI container with key as '{$key}'");
        }

        return $this->container[$key];
    }

    /**
     * @return array
     */
    public function getAllConfig()
    {
        return $this->getContainer(self::CONTAINER_CONFIG);
    }
}
<?php

namespace Ant\Application;

use Ant\Types\RouterConfig;

class Config
{
    const DI     = 'di';
    const ROUTER = 'router';

    /**
     * @var array
     */
    private $params = [];

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $dirPath
     *
     * @return $this
     */
    public function addConfigsFromDir($dirPath)
    {
        $directory = new \RecursiveDirectoryIterator($dirPath);
        $iterator  = new \RecursiveIteratorIterator($directory);
        /** @var \SplFileInfo $info */
        foreach ($iterator as $info) {
            if (in_array($info->getFileName(), ['.', '..', 'index.html'])) {
                continue;
            }
            $this->load($info->getPathname());
        }

        return $this;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    private function load($path)
    {
        $params       = $this->prepareParams((array)require_once($path));
        $this->params = array_replace_recursive($this->params, $params);

        return $this;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    private function prepareParams(array $params)
    {
        $result = $router = [];
        foreach ($params as $key => $value) {
            if ($key == self::ROUTER) {
                foreach ($value as $name => $item) {
                    if ($item instanceof RouterConfig) {
                        $router[$item->getName()] = $item->convertToConfigArray();
                    } else {
                        $router[$name] = $item;
                    }
                }
                continue;
            }
            if ($value instanceof RouterConfig) {
                $router[$value->getName()] = $value->convertToConfigArray();
                continue;
            }
            $result[$key] = $value;
        }
        $result[self::ROUTER] = $router;

        return $result;
    }
}
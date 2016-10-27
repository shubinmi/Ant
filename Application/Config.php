<?php

namespace Ant\Application;

class Config
{
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
        $params       = require_once($path);
        $this->params = array_replace_recursive($this->params, $params);

        return $this;
    }
}
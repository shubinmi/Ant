<?php

namespace Ant\Interfaces;

interface ViewPluginInterface
{
    /**
     * @param string $body
     *
     * @return string
     */
    public function apply($body);
}
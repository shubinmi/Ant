<?php

namespace Ant\Application;

class ViewLayoutElement
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var array
     */
    public $args = [];

    /**
     * @var string
     */
    public $name;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}
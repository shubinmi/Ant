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
    public $vars = [];

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

    /**
     * @param array $params
     *
     * @return array
     */
    public static function getErrorsByValidate(array $params)
    {
        $errors = [];
        if (empty($params['path'])) {
            $errors[] = 'Empty required property "path"';
        }
        if (empty($params['name'])) {
            $errors[] = 'Empty required property "name"';
        }

        return $errors;
    }
}
<?php

namespace Ant\Application;

class ViewLayoutElement
{
    const PROPERTY_PATH = 'path';
    const PROPERTY_VARS = 'vars';
    const PROPERTY_NAME = 'name';

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
        if (empty($params[self::PROPERTY_PATH])) {
            $errors[] = 'Empty required property "'. self::PROPERTY_PATH . '"';
        }
        if (empty($params[self::PROPERTY_NAME])) {
            $errors[] = 'Empty required property "' . self::PROPERTY_NAME . '"';
        }

        return $errors;
    }
}
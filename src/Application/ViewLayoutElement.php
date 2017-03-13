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

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @param array $vars
     *
     * @return $this
     */
    public function setVars($vars)
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
<?php

namespace Ant\Types;

class RouterConfig
{
    const PROPERTY_HANDLER    = 'handler';
    const PROPERTY_CONTROLLER = 'controller';
    const PROPERTY_ACTION     = 'action';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $methods = [];

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

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
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     *
     * @return $this
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function addMethod($method)
    {
        $this->methods[] = $method;
        return $this;
    }

    /**
     * @return $this
     */
    public function allowGET()
    {
        $this->methods[] = 'GET';
        return $this;
    }

    /**
     * @return $this
     */
    public function allowPOST()
    {
        $this->methods[] = 'POST';
        return $this;
    }

    /**
     * @return $this
     */
    public function allowPUT()
    {
        $this->methods[] = 'PUT';
        return $this;
    }

    /**
     * @return $this
     */
    public function allowDELETE()
    {
        $this->methods[] = 'DELETE';
        return $this;
    }

    /**
     * @return callable
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param callable $handler
     *
     * @return $this
     */
    public function setHandler(callable $handler)
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     *
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function convertToConfigArray()
    {
        if (!$this->getName()) {
            throw new \Exception('Empty required property "name"');
        }
        if (empty($this->getMethods())) {
            throw new \Exception('Empty required property "methods"');
        }
        if (!$this->getPath()) {
            throw new \Exception('Empty required property "path"');
        }
        if (
            !$this->getHandler()
            && (!$this->getController() || !$this->getAction())
        ) {
            throw new \Exception('Empty required property "handler"');
        }

        return [
            $this->getMethods(),
            $this->getPath(),
            [
                self::PROPERTY_HANDLER => $this->getHandler(),
                self::PROPERTY_CONTROLLER => $this->getController(),
                self::PROPERTY_ACTION => $this->getAction()
            ]
        ];
    }
}
<?php

namespace Ant\Application;

class View
{
    /**
     * @var callable[]
     */
    private $plugins = [];

    /**
     * @var ViewLayoutElement[]
     */
    private $layoutElements = [];

    /**
     * @var string
     */
    private $layoutPath;

    /**
     * @var array
     */
    private $layoutArgs = [];

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->layoutPath = __DIR__ . '/../View/layout.phtml';
        $this->addPlugin([$this, 'placeholderPlugin']);
        $this->setLayoutElements($params);
    }

    /**
     * @param callable $plugin
     *
     * @return $this
     */
    public function addPlugin(callable $plugin)
    {
        $this->plugins[] = $plugin;

        return $this;
    }

    /**
     * @param callable[] $plugins
     *
     * @return $this
     */
    public function setPlugins(array $plugins)
    {
        $this->plugins = $plugins;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        ob_start();
        header("Content-Type: text/html; charset=utf-8");
        if ($this->layoutPath) {
            foreach ($this->layoutArgs as $name => $value) {
                ${$name} = $value;
            }
            include "{$this->layoutPath}";
        } else {
            /** @var ViewLayoutElement $viewElement */
            $viewElement = array_shift($this->layoutElements);
            foreach ($viewElement->args as $name => $value) {
                ${$name} = $value;
            }
            include "{$viewElement->path}";
        }
        $body = ob_get_contents();
        ob_end_clean();

        $body = $this->applyPlugins($body);

        return $body;
    }

    /**
     * @param string $layoutPath
     *
     * @return $this
     */
    public function setLayoutPath($layoutPath)
    {
        $this->layoutPath = $layoutPath;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutLayout()
    {
        $this->layoutPath = '';

        return $this;
    }

    /**
     * @param array $layoutArgs
     *
     * @return $this
     */
    public function setLayoutArgs(array $layoutArgs)
    {
        $this->layoutArgs = $layoutArgs;

        return $this;
    }

    /**
     * @param array $args
     *
     * @return $this
     */
    public function addLayoutArgs(array $args)
    {
        foreach ($args as $key => $value) {
            $this->layoutArgs[$key] = $value;
        }

        return $this;
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    private function setLayoutElements(array $params)
    {
        if (!empty($params['elements'])) {
            foreach ($params['elements'] as $elementParams) {
                $this->layoutElements[$elementParams['name']] = new ViewLayoutElement($elementParams);
            }
        } else {
            $this->layoutElements[$params['name']] = new ViewLayoutElement($params);
        }

        return $this;
    }

    /**
     * @param array $elements
     *
     * @return $this
     */
    public function addLayoutElements(array $elements)
    {
        foreach ($elements as $elementName => $element) {
            if (empty($this->layoutElements[$elementName])) {
                $elementParams = $element;
            } elseif (!empty($this->layoutElements[$elementName]->args) && !empty($element['args'])) {
                $args =  array_merge((array)$this->layoutElements[$elementName]->args, $element['args']);
                $elementParams = array_merge((array)$this->layoutElements[$elementName], $element);
                $elementParams['args'] = $args;
            } else {
                $elementParams = array_merge((array)$this->layoutElements[$elementName], $element);
            }
            $this->layoutElements[$elementName] = new ViewLayoutElement($elementParams);
        }

        return $this;
    }

    /**
     * @param string $body
     *
     * @return string
     */
    private function applyPlugins($body)
    {
        $oldBody = '';
        while ($oldBody != $body) {
            $oldBody = $body;
            foreach ($this->plugins as $plugin) {
                if (!is_callable($plugin)) {
                    continue;
                }
                $body = call_user_func_array($plugin, [$body]);
            }
        }

        return $body;
    }

    /**
     * @param string $body
     *
     * @return string
     */
    private function placeholderPlugin($body)
    {
        preg_match_all("|{{(.*)}}|U", $body, $out, PREG_PATTERN_ORDER);
        $elements = $out[1];

        foreach ($elements as $element) {
            $html = '';
            if (!empty($this->layoutElements[$element])) {
                ob_start();
                header("Content-Type: text/html; charset=utf-8");
                if (!empty($this->layoutElements[$element]->args) && is_array($this->layoutElements[$element]->args)) {
                    foreach ($this->layoutElements[$element]->args as $name => $value) {
                        ${$name} = $value;
                    }
                }
                include "{$this->layoutElements[$element]->path}";
                $html = ob_get_contents();
                ob_end_clean();
            }
            $body = strtr($body, ['{{' . $element . '}}' => $html]);
        }

        return $body;
    }

}
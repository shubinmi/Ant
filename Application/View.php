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
    private $layoutVars = [];

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
            foreach ($this->layoutVars as $name => $value) {
                ${$name} = $value;
            }
            include "{$this->layoutPath}";
        } else {
            /** @var ViewLayoutElement $viewElement */
            $viewElement = array_shift($this->layoutElements);
            foreach ($viewElement->vars as $name => $value) {
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
     * @param array $layoutVars
     *
     * @return $this
     */
    public function setLayoutVars(array $layoutVars)
    {
        $this->layoutVars = $layoutVars;

        return $this;
    }

    /**
     * @param array $vars
     *
     * @return $this
     */
    public function addLayoutVars(array $vars)
    {
        foreach ($vars as $key => $value) {
            $this->layoutVars[$key] = $value;
        }

        return $this;
    }

    /**
     * @param array|array[]|ViewLayoutElement|ViewLayoutElement[] $elements
     *
     * @return $this
     * @throws \Exception
     */
    private function setLayoutElements($elements)
    {
        if ($elements instanceof ViewLayoutElement) {
            $this->layoutElements[$elements->name] = $elements;
        } elseif (is_array($elements) && empty($elements['name'])) {
            foreach ($elements as $element) {
                if ($element instanceof ViewLayoutElement) {
                    $this->layoutElements[$element->name] = $element;
                } else {
                    $this->validateParamsForViewElement($element)
                        ->layoutElements[$element['name']] = new ViewLayoutElement($element);
                }
            }
        } else {
            $this->validateParamsForViewElement($elements)->layoutElements[$elements['name']] =
                new ViewLayoutElement($elements);
        }

        return $this;
    }

    /**
     * @param array|ViewLayoutElement|ViewLayoutElement[] $elements
     *
     * @return $this
     */
    public function addLayoutElements($elements)
    {
        if ($elements instanceof ViewLayoutElement) {
            $elements = [
                $elements->name = [
                    'path' => $elements->path,
                    'vars' => $elements->vars
                ]
            ];
        }
        foreach ($elements as $elementName => $element) {
            if ($element instanceof ViewLayoutElement) {
                $element     = [
                    'name' => $element->name,
                    'path' => $elements->path,
                    'vars' => $elements->vars
                ];
            } else {
                $element['name'] = $elementName;
            }
            $this->validateParamsForViewElement($element);
            if (empty($this->layoutElements[$elementName])) {
                $elementParams = $element;
            } elseif (!empty($this->layoutElements[$elementName]->vars) && !empty($element['vars'])) {
                $vars                  =
                    array_merge((array)$this->layoutElements[$elementName]->vars, $element['vars']);
                $elementParams         = array_merge((array)$this->layoutElements[$elementName], $element);
                $elementParams['vars'] = $vars;
            } else {
                $elementParams = array_merge((array)$this->layoutElements[$elementName], $element);
            }
            $this->layoutElements[$elementName] = new ViewLayoutElement($elementParams);
        }

        return $this;
    }

    /**
     * @param array $params
     *
     * @return $this
     * @throws \Exception
     */
    private function validateParamsForViewElement(array $params)
    {
        $validateErrors = ViewLayoutElement::getErrorsByValidate($params);
        if (!empty($validateErrors)) {
            throw new \Exception(implode('; ', $validateErrors));
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
                if (!empty($this->layoutElements[$element]->vars) && is_array($this->layoutElements[$element]->vars)) {
                    foreach ($this->layoutElements[$element]->vars as $name => $value) {
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
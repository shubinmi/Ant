<?php

namespace Ant\Application;

use Ant\Interfaces\ViewPluginInterface;

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
     * @param callable|ViewPluginInterface $plugin
     *
     * @return $this
     * @throws \Exception
     */
    public function addPlugin($plugin)
    {
        if ($plugin instanceof ViewPluginInterface) {
            $plugin = [$plugin, 'apply'];
        }
        if (!is_callable($plugin)) {
            throw new \Exception('Incorrect view plugin. ' . json_encode($plugin));
        }
        $this->plugins[] = $plugin;

        return $this;
    }

    /**
     * @param callable[]|ViewPluginInterface[] $plugins
     *
     * @return $this
     */
    public function setPlugins(array $plugins)
    {
        $this->plugins = [];
        foreach ($plugins as $plugin) {
            $this->addPlugin($plugin);
        }

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
            $this->validateParamsForViewElement($elements)->layoutElements[(string)$elements['name']] =
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
                $elements->name => [
                    ViewLayoutElement::PROPERTY_PATH => $elements->path,
                    ViewLayoutElement::PROPERTY_NAME => $elements->vars
                ]
            ];
        }
        foreach ($elements as $elementName => $element) {
            if ($element instanceof ViewLayoutElement) {
                $element     = [
                    ViewLayoutElement::PROPERTY_NAME => $element->name,
                    ViewLayoutElement::PROPERTY_PATH => $element->path,
                    ViewLayoutElement::PROPERTY_VARS => $element->vars
                ];
                $elementName = $element[ViewLayoutElement::PROPERTY_NAME];
            } else {
                $element[ViewLayoutElement::PROPERTY_NAME] = $elementName;
            }
            $this->validateParamsForViewElement($element);
            if (empty($this->layoutElements[$elementName])) {
                $elementParams = $element;
            } elseif (
                !empty($this->layoutElements[$elementName]->vars)
                && !empty($element[ViewLayoutElement::PROPERTY_VARS])
            ) {
                $vars          = array_merge(
                    (array)$this->layoutElements[$elementName]->vars, $element[ViewLayoutElement::PROPERTY_VARS]
                );
                $elementParams = array_merge((array)$this->layoutElements[$elementName], $element);

                $elementParams[ViewLayoutElement::PROPERTY_VARS] = $vars;
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
                    foreach ($this->layoutElements[$element]->vars as $nameAntVar => $valueAntVar) {
                        ${$nameAntVar} = $valueAntVar;
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
<?php

namespace Ant\Plugin\Seo;

class Generic implements SeoInterface
{
    /**
     * @var string
     */
    private $dom;

    /**
     * @var array
     */
    private $attr;

    /**
     * @param string $dom
     * @param array $attr
     */
    public function __construct($dom, array $attr = [])
    {
        $this->dom  = $dom;
        $this->attr = $attr;
    }

    /**
     * @return mixed
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @param mixed $dom
     *
     * @return $this
     */
    public function setDom($dom)
    {
        $this->dom = $dom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * @param mixed $attr
     *
     * @return $this
     */
    public function setAttr($attr)
    {
        $this->attr = $attr;
        return $this;
    }

    /**
     * @param array $attr
     *
     * @return $this
     */
    public function addAttr(array $attr)
    {
        $this->attr[] = $attr;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $result = '<' . $this->getDom();
        foreach ($this->getAttr() as $key => $value) {
            $result .= " {$key}='{$value}' ";
        }
        $result .= ' />';

        return $result;
    }
}
<?php

namespace Ant\Plugin\Seo;

class Meta implements SeoInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $name
     * @param string $content
     */
    public function __construct($name, $content)
    {
        $this->name    = $name;
        $this->content = $content;
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

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        return "<meta name='{$this->getName()}' content='{$this->getContent()}' />";
    }
}
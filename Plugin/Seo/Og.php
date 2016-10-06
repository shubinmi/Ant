<?php

namespace Ant\Plugin\Seo;

class Og implements SeoInterface
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $property
     * @param string $content
     */
    public function __construct($property, $content)
    {
        $this->property = $property;
        $this->content  = $content;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $property
     *
     * @return $this
     */
    public function setProperty($property)
    {
        $this->property = $property;
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
        return "<meta property='og:{$this->getProperty()}' content='{$this->getContent()}' />";
    }
}
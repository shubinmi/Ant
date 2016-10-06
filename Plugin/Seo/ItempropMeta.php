<?php

namespace Ant\Plugin\Seo;

class ItempropMeta implements SeoInterface
{
    /**
     * @var string
     */
    private $itemprop;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $itemprop
     * @param string $content
     */
    public function __construct($itemprop, $content)
    {
        $this->itemprop = $itemprop;
        $this->content  = $content;
    }

    /**
     * @return string
     */
    public function getItemprop()
    {
        return $this->itemprop;
    }

    /**
     * @param string $itemprop
     *
     * @return $this
     */
    public function setItemprop($itemprop)
    {
        $this->itemprop = $itemprop;
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
        return "<meta itemprop='{$this->getItemprop()}' content='{$this->getContent()}' />";
    }
}
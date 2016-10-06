<?php

namespace Ant\Plugin\Seo;

class ItempropLink implements SeoInterface
{
    /**
     * @var string
     */
    private $itemprop;

    /**
     * @var string
     */
    private $href;

    /**
     * @param string $itemprop
     * @param string $href
     */
    public function __construct($itemprop, $href)
    {
        $this->itemprop = $itemprop;
        $this->href     = $href;
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
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param string $href
     *
     * @return $this
     */
    public function setHref($href)
    {
        $this->href = $href;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        return "<link itemprop='{$this->getItemprop()}' href='{$this->getHref()}' />";
    }
}
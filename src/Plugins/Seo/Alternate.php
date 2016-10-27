<?php

namespace Ant\Plugins\Seo;

class Alternate implements SeoInterface
{
    /**
     * @var string
     */
    private $hreflang;

    /**
     * @var string
     */
    private $href;

    /**
     * @param string $hreflang
     * @param string $href
     */
    public function __construct($hreflang, $href)
    {
        $this->hreflang = $hreflang;
        $this->href     = $href;
    }

    /**
     * @return string
     */
    public function getHreflang()
    {
        return $this->hreflang;
    }

    /**
     * @param string $hreflang
     *
     * @return $this
     */
    public function setHreflang($hreflang)
    {
        $this->hreflang = $hreflang;
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
        return "<link rel='alternate' hreflang='{$this->getHreflang()}' href='{$this->getHref()}' />";
    }
}
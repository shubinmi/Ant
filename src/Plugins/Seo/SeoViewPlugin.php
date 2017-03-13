<?php

namespace Ant\Plugins\Seo;

use Ant\Interfaces\ViewPluginInterface;

class SeoViewPlugin implements ViewPluginInterface
{
    /**
     * @var Seo
     */
    private $seo;

    /**
     * @param Seo $seo
     */
    public function __construct(Seo $seo)
    {
        $this->seo = $seo;
    }

    /**
     * @param string $body
     *
     * @return string
     */
    public function apply($body)
    {
        $body = str_ireplace('<seoblock/>', $this->seo->render(), $body);
        return $body;
    }
}
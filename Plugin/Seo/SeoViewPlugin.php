<?php

namespace Ant\Plugin\Seo;

class SeoViewPlugin
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
<?php

namespace AntExample\Common\Helpers;

use Ant\Plugins\Seo\ItempropMeta;
use Ant\Plugins\Seo\Meta;
use Ant\Plugins\Seo\Og;
use Ant\Plugins\Seo\Seo;
use Ant\Plugins\Seo\SeoViewPlugin;
use Psr\Http\Message\UriInterface;

class SeoHelper
{
    /**
     * @param UriInterface $uri
     *
     * @return SeoViewPlugin
     */
    public static function getSeoPlugin(UriInterface $uri)
    {
        $seo = new Seo();
        $seo->setTitle('Ant test seo page.');
        $seo->getMetas()->append(new Meta('Description', 'Ant test seo page. Build on antFramework.'));
        $seo->getMetas()->append(new Meta('Keywords', 'Ant Framework'));
        $seo->getMetas()->append(new Meta('googlebot', 'NOODP'));
        $seo->getOgs()->append(new Og('title', 'Ant test seo page.'));
        $seo->getOgs()->append(new Og('site_name', 'Ant test seo page.'));
        $seo->getOgs()->append(new Og('description', 'Ant test seo page.'));
        $seo->getOgs()->append(new Og('type', 'website'));
        $seo->getOgs()->append(new Og('url', $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath()));
        $seo->getItempropMetas()->append(new ItempropMeta('name', 'Ant test seo page.'));
        $seo->getItempropMetas()->append(new ItempropMeta('description', 'Ant test seo page.'));

        return new SeoViewPlugin($seo);
    }
}
<?php

namespace AntExample\Application\Controllers;

use Ant\Plugins\Seo\ItempropMeta;
use Ant\Plugins\Seo\Meta;
use Ant\Plugins\Seo\Og;
use Ant\Plugins\Seo\Seo;
use Ant\Plugins\Seo\SeoViewPlugin;
use AntExample\Application\Stories\MainStory;
use AntExample\Common\Factories\CoreController;

class Main extends CoreController
{
    /**
     * @var MainStory
     */
    private $mainStory;

    public function init()
    {
        $this->mainStory = new MainStory($this->getDi());
    }

    public function mainAction()
    {
        $name = $this->getRequestUriParam('name');

        if ($this->mainStory->isUserAuthSuccess()) {
            $this->mainStory->rewriteUserName($name);
        } else {
            $this->mainStory->createUser($name);
        }

        $elements = [
            // It means that {{body}} at layout.phtml (and at other view elements) will be
            // replaced to content from main.phtml
            'body' => [
                'path' => __DIR__ . '/../Views/main.phtml',
                'vars' => [
                    'name'  => $name,
                    'error' => $this->mainStory->getError(),
                    'log'   => $this->mainStory->getLog()
                ]
            ]
        ];

        return $this->getView()->addLayoutElements($elements);
    }

    public function seoAction()
    {
        $uri = $this->getRequest()->getUri();
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
        $seoViewPlugin = new SeoViewPlugin($seo);

        $elements = [
            // It means that {{body}} at layout.phtml (and at other view elements) will be
            // replaced to content from main.phtml
            'body' => [
                'path' => __DIR__ . '/../Views/main.phtml',
                'vars' => [
                    'name'  => 'friend!',
                    'error' => 'Error is\'t found.',
                    'log'   => 'Seo plugin applied success. Open source code of page.'
                ]
            ]
        ];

        return $this->getView()
            ->addPlugin($seoViewPlugin)
            ->addLayoutElements($elements);
    }

    public function alohaAction()
    {
        return json_encode(
            [
                'status' => 200,
                'body'   => 'Aloha'
            ]
        );
    }
}
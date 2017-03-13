<?php

namespace AntExample\Application\Controllers;

use Ant\Application\ViewLayoutElement;
use AntExample\Application\Stories\MainStory;
use AntExample\Common\Factories\CoreController;
use AntExample\Common\Helpers\SeoHelper;

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
        $elements      = [
            // It means that {{body}} at layout.phtml (and at other view elements) will be
            // replaced to content from main.phtml
            'body' => [
                'path' => __DIR__ . '/../Views/main.phtml',
                'vars' => [
                    'name'  => 'friend!',
                    'error' => 'Error is\'t found.',
                    'log'   => 'Seo plugin applied success. Open source code of page.'
                ]
            ],
            // It means that {{head}} at layout.phtml (and at other view elements) will be
            // replaced to content from head.phtml
            (new ViewLayoutElement())
                ->setName('head')
                ->setPath(__DIR__ . '/../Views/head.phtml'),
        ];
        $seoViewPlugin = SeoHelper::getSeoPlugin($this->getRequest()->getUri());

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
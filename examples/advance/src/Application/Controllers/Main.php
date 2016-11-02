<?php

namespace Application\Controllers;

use Ant\Application\Controller;
use Ant\Application\View;
use Application\Services\MainStory;

class Main extends Controller
{
    /**
     * @var MainStory
     */
    private $mainStory;

    public function __construct()
    {
        $this->mainStory = new MainStory();
    }

    public function mainAction()
    {
        $name = $this->getRequestUriParam('name');

        if ($this->mainStory->isUserAuthSuccess()) {
            $this->mainStory->rewriteUserName($name);
        } elseif ($name) {
            $this->mainStory->createUser($name);
        }

        $elements = [
            // It mean that {{body}} at layout.phtml (and at other view elements) will be
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

    /**
     * @return View
     */
    private function getView()
    {
        $view = new View();
        $view->setLayoutPath(__DIR__ . '/../Views/layout.phtml');

        return $view;
    }
}
<?php

namespace Controllers;

use Ant\Application\Controller;
use Ant\Application\View;

class Main extends Controller
{
    public function mainAction()
    {
        $name      = $this->getRequestUriParam('name');
        $elements = [
            // It mean that {{body}} at layout.phtml (and at other view elements) will be
            // replaced to content from main.phtml
            'body' => [
                'path' => 'Views/main.phtml',
                'vars' => ['name' => $name]
            ]
        ];

        return $this->getView()->addLayoutElements($elements);
    }

    private function getView()
    {
        $view = new View();
        $view->setLayoutPath('Views/layout.phtml');

        return $view;
    }
}
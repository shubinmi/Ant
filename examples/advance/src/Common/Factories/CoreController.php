<?php

namespace Common\Factories;

use Ant\Application\Controller;
use Ant\Application\View;

class CoreController extends Controller
{
    /**
     * @var View
     */
    private $view;

    /**
     * @return View
     */
    protected function getView()
    {
        if (!$this->view) {
            $view = new View();
            $view->setLayoutPath(__DIR__ . '/../Views/layout.phtml');
            $this->view = $view;
        }
        return $this->view;
    }
}
<?php

namespace Ant\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class Application
{
    /**
     * @var DI
     */
    private $di;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct()
    {
        $this->config   = new Config();
        $this->di       = new DI();
        $this->router   = new Router();
        $this->request  = ServerRequestFactory::fromGlobals();
        $response = new Response();
        $this->response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * @param array $configDirPaths
     *
     * @return $this
     */
    public function loadConfig(array $configDirPaths)
    {
        foreach ($configDirPaths as $path) {
            $this->config->addConfigsFromDir($path);
        }

        $this->di     = new DI($this->config->getParams());
        $this->router = new Router($this->config->getParams()['router']);

        return $this;
    }

    public function run()
    {
        $controllerDeclaration = $this->router->dispatch($this->request);
        $this->request         = $this->request->withQueryParams($controllerDeclaration['params']);
        $view                  = $this->getControllerResult($controllerDeclaration['handler']);
        $this->writeBody($view)->render();
    }

    /**
     * @param View|string $view
     *
     * @return $this
     */
    private function writeBody($view)
    {
        if ($view instanceof View) {
            $this->response->getBody()->write($view->getBody());
        } else {
            $this->response->getBody()->write((string)$view);
        }

        return $this;
    }

    /**
     * @param array $handler
     *
     * @return View|string
     */
    private function getControllerResult(array $handler)
    {
        $controllerClass = '\\' . $handler['controller'];
        $action          = $handler['action'];
        /** @var Controller $controller */
        $controller = new $controllerClass();
        $controller->setRequest($this->request);
        $controller->setDi($this->di);
        $controller->setResponse($this->response);

        $view           = $controller->{$action}();
        $this->response = $controller->getResponse();

        return $view;
    }

    private function render()
    {
        header(
            sprintf(
                'HTTP/%s %s %s',
                $this->response->getProtocolVersion(),
                $this->response->getStatusCode(),
                $this->response->getReasonPhrase()
            )
        );

        foreach ($this->response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        ob_start();
        echo $this->response->getBody();
        ob_end_flush();
    }
}
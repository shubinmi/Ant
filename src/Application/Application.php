<?php

namespace Ant\Application;

use Ant\Types\RouterConfig;
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
        $response       = new Response();
        $this->response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * @param array $configDirPaths
     *
     * @return $this
     * @throws \Exception
     */
    public function loadConfig(array $configDirPaths)
    {
        try {
            foreach ($configDirPaths as $path) {
                $this->config->addConfigsFromDir($path);
            }
            $this->di     = new DI($this->config->getParams());
            $this->router = new Router($this->config->getParams()[Config::ROUTER]);
        } catch (\Exception $e) {
            $html = <<<HTML
<pre>Can't load config.<br>Error code: {$e->getCode()}<br>{$e->getMessage()}<br>{$e->getTraceAsString()}</pre>
HTML;
            $this->response->withStatus(500, 'Application error');
            $this->writeBody($html)->render();
        }

        return $this;
    }

    public function run()
    {
        try {
            $controllerDeclaration = $this->router->dispatch($this->request);
            $this->request         = $this->request->withQueryParams($controllerDeclaration['params']);
            $view                  = $this->getControllerResult($controllerDeclaration['handler']);
            $this->writeBody($view)->render();
        } catch (\Exception $e) {
            $html = <<<HTML
<pre>Error code: {$e->getCode()}<br>{$e->getMessage()}<br>{$e->getTraceAsString()}</pre>
HTML;
            $this->response->withStatus(500, 'Application error');
            $this->writeBody($html)->render();
        }
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
     * @throws \Exception
     */
    private function getControllerResult(array $handler)
    {
        try {
            if (
                !empty($handler[RouterConfig::PROPERTY_HANDLER])
                && is_callable($handler[RouterConfig::PROPERTY_HANDLER])
            ) {
                $callable = $handler[RouterConfig::PROPERTY_HANDLER];
                if (is_string($callable)) {
                    $callable = explode('::', $callable);
                }
                $controllerClass = get_class($callable[0]);
                $action          = $callable[1];
            } else {
                $controllerClass = '\\' . $handler[RouterConfig::PROPERTY_CONTROLLER];
                $action          = $handler[RouterConfig::PROPERTY_ACTION];
            }
        } catch (\Exception $e) {
            throw new \Exception('Empty handler on router');
        }
        if (!class_exists($controllerClass)) {
            throw new \Exception('Can\'t find controller class ' . $controllerClass);
        }
        /** @var Controller $controller */
        $controller = new $controllerClass();
        if ($controller instanceof Controller) {
            $controller->setRequest($this->request);
            $controller->setDi($this->di);
            $controller->setResponse($this->response);
            $controller->init();
        }
        if (!method_exists($controller, $action)) {
            throw new \Exception('Can\'t find method "' . $action . '" at ' . $controllerClass);
        }
        $view = $controller->{$action}();
        if ($controller instanceof Controller) {
            $this->response = $controller->getResponse();
        }

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
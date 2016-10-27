<?php

namespace Ant\Application;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var array
     */
    private $routerParams = [];

    /**
     * @param array $routerParams
     */
    public function __construct(array $routerParams = [])
    {
        $this->routerParams = $routerParams;
        $this->dispatcher = \FastRoute\simpleDispatcher(
            function (RouteCollector $r) {
                foreach ($this->routerParams as $routeConf) {
                    call_user_func_array([$r, 'addRoute'], $routeConf);
                }
            }
        );
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        $result = [
            'handler' => [],
            'params'  => []
        ];

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $result['handler'] = [
                    'controller' => 'Ant\Application\Controller',
                    'action'     => 'notFound',
                ];
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $result['handler'] = [
                    'controller' => 'Ant\Application\Controller',
                    'action'     => 'notFound',
                ];
                break;
            case Dispatcher::FOUND:
                $result['handler'] = $routeInfo[1];
                $result['params']  = $routeInfo[2];
                break;
        }

        return $result;
    }
}
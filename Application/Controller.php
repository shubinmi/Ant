<?php

namespace Ant\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller
{
    /**
     * @var DI
     */
    private $di;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @return View
     */
    public function notFound()
    {
        $this->response = $this->response->withStatus(404);
        return new View(['name' => 'body', 'path' => __DIR__ . '/../View/404.phtml']);
    }

    /**
     * @return DI
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param DI $di
     *
     * @return $this
     */
    public function setDi($di)
    {
        $this->di = $di;
        return $this;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getRequestUriParams()
    {
        return $this->getRequest()->getQueryParams();
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function getRequestUriParam($name)
    {
        return empty($this->getRequestUriParams()[$name]) ? '' : $this->getRequestUriParams()[$name];
    }

    /**
     * @return array
     */
    public function getRequestPostParams()
    {
        $params = [];
        parse_str($this->getRequest()->getBody()->getContents(), $params);

        return $params;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getRequestPostParam($name)
    {
        return empty($this->getRequestPostParams()[$name]) ? null : $this->getRequestPostParams()[$name];
    }

    /**
     * @return array
     */
    public function getRequestGetParams()
    {
        $params = [];
        parse_str($this->getRequest()->getUri()->getQuery(), $params);

        return $params;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getRequestGetParam($name)
    {
        return empty($this->getRequestGetParams()[$name]) ? null : $this->getRequestGetParams()[$name];
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return $this
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return $this
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
        return $this;
    }
}
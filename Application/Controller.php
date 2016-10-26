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
    public function requestUriParams()
    {
        return $this->getRequest()->getQueryParams();
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function requestUriParam($name)
    {
        return empty($this->requestUriParams()[$name]) ? '' : $this->requestUriParams()[$name];
    }

    /**
     * @return array
     */
    public function requestPostParams()
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
    public function requestPostParam($name)
    {
        return empty($this->requestPostParams()[$name]) ? null : $this->requestPostParams()[$name];
    }

    /**
     * @return array
     */
    public function requestGetParams()
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
    public function requestGetParam($name)
    {
        return empty($this->requestGetParams()[$name]) ? null : $this->requestGetParams()[$name];
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
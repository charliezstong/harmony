<?php
namespace WoohooLabs\Harmony;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Harmony\Dispatcher\DispatcherInterface;
use WoohooLabs\Harmony\Middleware\MiddlewareInterface;

class Harmony
{
    /**
     * @var array
     */
    protected $middlewares;

    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var \WoohooLabs\Harmony\Dispatcher\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @return $this
     */
    public static function build()
    {
        return new Harmony();
    }

    public function live()
    {
        foreach ($this->middlewares as $middleware) {
            /** @var \WoohooLabs\Harmony\Middleware\MiddlewareInterface $middleware */
            $middleware->execute($this);
        }
    }

    /**
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * @param string $id
     * @return \WoohooLabs\Harmony\Middleware\MiddlewareInterface|null
     */
    public function getMiddleware($id)
    {
        return isset($this->middlewares[$id]) ? $this->middlewares[$id] : null;
    }

    /**
     * @param \WoohooLabs\Harmony\Middleware\MiddlewareInterface $middleware
     * @return $this
     */
    public function addMiddleware(MiddlewareInterface $middleware)
    {
        $this->middlewares[$middleware->getId()]= $middleware;

        return $this;
    }

    /**
     * @param \WoohooLabs\Harmony\Middleware\MiddlewareInterface $middleware
     * @return $this
     */
    public function removeMiddleware(MiddlewareInterface $middleware)
    {
        unset($this->middlewares[$middleware->getId()]);

        return $this;
    }

    /**
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param \Interop\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $responder
     */
    public function setResponse(ResponseInterface $responder)
    {
        $this->response = $responder;
    }

    /**
     * @return \WoohooLabs\Harmony\Dispatcher\DispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param \WoohooLabs\Harmony\Dispatcher\DispatcherInterface $dispatcher
     */
    public function setDispatcher(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}

<?php
namespace King23\Http;

use King23\DI\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class MiddlewareQueue implements MiddlewareQueueInterface
{
    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @var string[]
     */
    protected $queue = [];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param LoggerInterface $log
     * @param ContainerInterface $container
     */
    public function __construct(LoggerInterface $log, ContainerInterface $container)
    {
        $this->log = $log;
        $this->container = $container;
    }

    /**
     * register a classname as part of the middleware queue
     *
     * @param $className
     * @return void
     */
    public function addMiddleware($className)
    {
        $this->queue[] = $className;
    }

    /**
     * execute the queue
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function run(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        return $this($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        // if the queue is empty, we have reached the lowest level
        if(count($this->queue) == 0) {
            return $response;
        }

        /** @var callable $middleware */
        $middleware = $this->container->getInstanceOf(array_shift($this->queue));
        return $middleware($request, $response, $this);
    }
}

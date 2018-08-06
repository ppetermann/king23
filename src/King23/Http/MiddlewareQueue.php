<?php
/*
 MIT License
 Copyright (c) 2010 - 2015 Peter Petermann

 Permission is hereby granted, free of charge, to any person
 obtaining a copy of this software and associated documentation
 files (the "Software"), to deal in the Software without
 restriction, including without limitation the rights to use,
 copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the
 Software is furnished to do so, subject to the following
 conditions:

 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 OTHER DEALINGS IN THE SOFTWARE.

*/
namespace King23\Http;

use King23\DI\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class MiddlewareQueue implements MiddlewareQueueInterface, RequestHandlerInterface
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
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param LoggerInterface $log
     * @param ContainerInterface $container
     * @param ResponseInterface $response
     */
    public function __construct(LoggerInterface $log, ContainerInterface $container, ResponseInterface $response)
    {
        $this->log = $log;
        $this->container = $container;
        $this->response = $response;
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
     * @return ResponseInterface
     * @throws \King23\DI\Exception\NotFoundException
     * @throws MiddlewareDoesNotImplementInterfaceException
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface {
        return $this($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \King23\DI\Exception\NotFoundException
     * @throws MiddlewareDoesNotImplementInterfaceException
     */
    public function __invoke(
        ServerRequestInterface $request
    ) {
        // if the queue is empty, we have reached the lowest level
        if(count($this->queue) == 0) {
            // @todo check if we need a default error response here
            return $this->response;
        }

        /** @var MiddlewareInterface $middleware */
        $middleware = $this->container->getInstanceOf(array_shift($this->queue));

        if (!($middleware instanceof MiddlewareInterface)) {
            throw new MiddlewareDoesNotImplementInterfaceException();
        }

        return $middleware->process($request, $this);
    }
}

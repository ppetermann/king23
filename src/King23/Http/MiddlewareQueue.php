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

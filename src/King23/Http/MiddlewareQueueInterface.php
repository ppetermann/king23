<?php
namespace King23\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareQueueInterface
{

    /**
     * register a classname as part of the middleware queue
     *
     * @param $className
     * @return void
     */
    public function addMiddleware($className);

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
    );
}

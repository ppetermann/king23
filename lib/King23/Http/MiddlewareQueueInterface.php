<?php
namespace King23\Http;

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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function run(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    );
}

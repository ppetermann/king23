<?php
namespace King23\Http;

interface RouterInterface
{
    /**
     * @param string $route
     * @param string $class
     * @param string $action
     * @param array $parameters
     * @param array $hostparameters
     * @return static
     */
    public function addRoute($route, $class, $action, $parameters = [], $hostparameters = []);

    /**
     * @param string $baseHost
     * @return static
     */
    public function setBaseHost($baseHost = null);

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response,
        callable $next
    );
}

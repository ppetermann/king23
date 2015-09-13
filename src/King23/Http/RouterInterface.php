<?php
namespace King23\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    );
}

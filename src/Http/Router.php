<?php
/*
 MIT License
 Copyright (c) 2010 - 2018 Peter Petermann

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

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * King23_Router class, allowing the matching of URL -> classmethod
 */
class Router implements RouterInterface
{
    /**
     * Array for storing known routes
     *
     * @var array
     */
    protected $routes = [];

    /**
     * String containing the basis host of the application, if this is set
     * this parameter will be removed from the hostname before hostparameters are extracted,
     * so having a low parameter count won't falsify the parameters by using the basic host as parameters
     *
     * @var string
     */
    protected $baseHost = null;

    /**
     * @var LoggerInterface
     */
    protected $log;

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
     * add route to list of known routes
     *
     * @param String $route beginning string of the route
     * @param String $class to be used for this route
     * @param String $action method to be called
     * @param string[] $parameters list of parameters that should be retrieved from url
     * @param array $hostparameters - allows to use subdomains as parameters
     * @return self
     */
    public function addRoute($route, $class, $action, $parameters = [], $hostparameters = [])
    {
        $this->log->debug('adding route : ' . $route . ' to class ' . $class . ' and action ' . $action);

        $this->routes[$route] = [
            "class" => $class,
            "action" => $action,
            "parameters" => $parameters,
            "hostparameters" => $hostparameters
        ];
        return $this;
    }

    /**
     * method to set the basicHost for hostparameters in routing
     *
     * @see King23_Router::$basicHost
     * @param String $baseHost
     * @return self
     */
    public function setBaseHost($baseHost = null)
    {
        $this->log->debug('Setting Router baseHost to ' . $baseHost);
        $this->baseHost = $baseHost;
        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     * @throws \King23\Controller\Exceptions\ActionDoesNotExistException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $next) : ResponseInterface {
        $this->log->debug('Dispatching request for ' . $request->getUri()->getPath());

        // sort routes
        uksort(
            $this->routes,
            function ($a, $b) {
                return strlen($a) < strlen($b);
            }
        );
        foreach ($this->routes as $route => $info) {
            // check if route is matched
            if (substr($request->getUri()->getPath(), 0, strlen($route)) == $route) {
                $this->log->debug('route ' . $route . ' matches ' . $request->getUri()->getPath());
                return $this->handleRoute($info, $request, $route);
            }
        }
        return $next->handle($request);
    }

    /**
     * Handle a regular route
     *
     * @param array $info
     * @param ServerRequestInterface $request
     * @param string $route
     * @return ResponseInterface
     * @throws \King23\Controller\Exceptions\ActionDoesNotExistException
     */
    private function handleRoute($info, ServerRequestInterface $request, $route) : ResponseInterface
    {
        // initialize router attributes for the request
        $attributes = [
            'params' => [
                'url' => [],
                'host' => []
            ]
        ];

        // prepare parameters
        if ($paramstr = substr($request->getUri()->getPath(), strlen($route))) {
            $attributes['params']['url'] = $this->filterParameters($info['parameters'], explode("/", $paramstr));
        }

        // check host parameters
        if (count($info["hostparameters"]) > 0) {
            $attributes['params']['host'] = $this->extractHostParameters($request, $info);
        }

        // put route parameters into king23.router.parameters attribute
        $request = $request->withAttribute('king23.router', $attributes);

        /** @var \King23\Controller\Controller $controller */
        $controller = $this->container->get($info["class"]);

        return $controller->dispatch($info["action"], $request);
    }

    /**
     * @param array $info
     * @param array $params
     * @return array
     */
    private function filterParameters($info, $params)
    {
        $parameters = [];
        foreach ($info as $key => $value) {
            if (isset($params[$key])) {
                $parameters[$value] = urldecode($params[$key]);
            } else {
                $parameters[$value] = null;
            }
        }
        return $parameters;
    }

    /**
     * extract parameters from hostname
     *
     * @param ServerRequestInterface $request
     * @param array $info
     * @return array
     */
    private function extractHostParameters($request, $info)
    {
        $hostname = $this->cleanHostName($request);

        if (empty($hostname)) {
            $params = [];
        } else {
            $params = array_reverse(explode(".", $hostname));
        }

        $parameters = $this->filterParameters($info['hostparameters'], $params);
        return $parameters;
    }

    /**
     * will get hostname, and clean basehost off it
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    private function cleanHostName(ServerRequestInterface $request)
    {

        if (is_null($this->baseHost)) {
            $hostname = $request->getUri()->getHost();
        } else {
            $hostname = str_replace($this->baseHost, "", $request->getUri()->getHost());
        }

        if (substr($hostname, -1) == ".") {
            $hostname = substr($hostname, 0, -1);
        }

        return $hostname;
    }
}

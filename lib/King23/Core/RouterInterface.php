<?php
namespace King23\Core;

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
     * this methods allows to register sub-routers if needed
     * @param string $route
     * @param RouterInterface $router
     * @return static
     */
    public function addRouter($route, \King23\Core\RouterInterface $router);

    /**
     * @deprecated
     * @param string $baseHost
     * @return static
     */
    public function setBaseHost($baseHost = null);
}

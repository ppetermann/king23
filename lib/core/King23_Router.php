<?php
class King23_Router implements King23_Singleton
{
    private static $myInstance;
    private $routes = array();

    /**
     * @return King23_Router
     */
    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new King23_Router();
        return self::$myInstance;
    }

    public function addRoute($route, $class, $action,$parameters = array())
    {
        $this->routes[$route] = array("class" => $class, "action" => $action, "parameters" => $parameters);
    }

    public function dispatch($request)
    {
        foreach($this->routes as $route => $info)
        {
            if(substr($request, 0, strlen($route)) == $route)
            {
                $class = $info["class"];
                $parameters = array();
                if($paramstr = substr($request, strlen($route)))
                {
                    $params = explode("/", $paramstr);
                    foreach($info["parameters"] as $key => $value)
                    {
                        if(isset($params[$key]))
                            $parameters[$value] = $params[$key];
                        else
                            $parameters[$value] = null;
                    }
                }
                $view = new $class();
                $view->dispatch($info["action"], $parameters);
                break;
            }
        }
    }
}
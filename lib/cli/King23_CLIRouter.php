<?php
/**
 * Class for old CLIView routing
 * @deprecated
 */
class King23_CLIRouter implements King23_Singleton
{
    private static $myInstance;
    private $routes = array();

    /**
     * @return King23_CLIRouter
     */
    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new King23_CLIRouter();
        return self::$myInstance;
    }

    public function addRoute($route, $class, $action,$parameters = array())
    {
        $this->routes[$route] = array("class" => $class, "action" => $action, "parameters" => $parameters);
    }

    public function dispatch($request)
    {
        array_shift($request); // lets get rid of script name =)
        $dest = array_shift($request);
        foreach($this->routes as $route => $info)
        {
            if($dest == $route)
            {
                $parameters = array();
                foreach($info["parameters"] as $key => $value)
                {
                    if(isset($request[$key]))
                        $parameters[$value] = $request[$key];
                    else
                        $parameters[$value] = null;
                }
                $class = $info["class"];
                $instance = new $class();
                $instance->dispatch($info["action"], $parameters);
            }
        }
    }
}
<?php
/*
 MIT License
 Copyright (c) 2010 Peter Petermann

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

/**
 * King23_Router class, allowing the matching of URL -> classmethod
 */
class King23_Router implements King23_Singleton
{
    /**
     * Singleton instance
     * @var King23_Router
     */
    private static $myInstance;
    
    /**
     * Array for storing known routes
     * @var array 
     */
    private $routes = array();

    /**
     * Singleton Instance
     * @return King23_Router
     */
    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new King23_Router();
        return self::$myInstance;
    }

    /**
     * add route to list of known routes
     * @param String $route beginning string of the route
     * @param String $class to be used for this route
     * @param String $action method to be called
     * @param array $parameters list of parameters that should be retrieved from
     *  url
     */
    public function addRoute($route, $class, $action,$parameters = array())
    {
        $this->routes[$route] = array("class" => $class, "action" => $action, "parameters" => $parameters);
    }

    /**
     * execute url request to method call
     * @param string $request
     */
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

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
     * String containing the basis host of the application, if this is set 
     * this parameter will be removed from the hostname before hostparameters are extracted,
     * so having a low parameter count won't falsify the parameters by using the basic host as parameters
     * @var string
     */
    private $basicHost = null;

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
     * @param array $parameters list of parameters that should be retrieved from url
     * @param array $hostparameters - allows to use subdomains as parameters 
     */
    public function addRoute($route, $class, $action,$parameters = array(), $hostparameters = array())
    {
        $this->routes[$route] = array("class" => $class, "action" => $action, "parameters" => $parameters, "hostparameters" => $hostparameters);
    }

    /**
     * Add a router for subroutes
     * @param string $route the route used to trigger usage of subrouter
     * @param King23_Router $router the router object
     */
    public function addRouter($route, King23_Router $router)
    {
        $this->routes[$route] = array("router" => $router);
    }


    /**
     * method to set the basicHost for hostparameters in routing
     * @see King23_Router::$basicHost
     * @param string baseHost
     */
    public function setBaseHost($baseHost = null)
    {
        $this->baseHost = $baseHost;
    }

    /**
     * execute url request to method or subouter call
     * @param string $request
     */
    public function dispatch($request)
    {
        foreach($this->routes as $route => $info)
        {
            // check if route is matched
            if(substr($request, 0, strlen($route)) == $route)
            {
                // is this a sub router?
                if(isset($info["router"]))
                {   
                    if($paramstr = substr($request, strlen($route)))
                    {  
                        return $info["router"]->dispatch($paramstr);
                    } else { // if after the match nothing is left, lets call default route..
                        return $info["router"]->dispatch("/");
                    }
                }
                else // otherwise its a regular (direct) route
                {
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

                    if(count($info["hostparameters"])>0) // if we have parameters that we extract from the host, then this is going to happen here.
                    {
                        if(is_null($this->baseHost))
                            $hostname = $_SERVER["SERVER_NAME"];
                        else
                            $hostname = str_replace($this->baseHost, "", $_SERVER["SERVER_NAME"]);
                       
                        if(substr($hostname, -1) == ".")
                            $hostname = substr($hostname, 0, -1);
                        
                        if(empty($hostname))
                            $params = array();
                        else 
                            $params = array_reverse(explode(".", $hostname));
                        
                        foreach($info["hostparameters"] as $key => $value)
                        {
                            if(isset($params[$key]) && !empty($params[$key]))
                                $parameters[$value] = $params[$key];
                            else
                                $parameters[$value] = null;
                        }
                    }
                    $class = $info["class"];
                    $view = new $class();
                    return $view->dispatch($info["action"], $parameters);                    
                }
                break;
            }
        }
    }
}

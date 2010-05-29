<?php
/*
 LICENSE
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
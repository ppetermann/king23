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
 * Singleton object to store global data
 */
class King23_Registry implements King23_Singleton
{
    /**
     * Instance for singleton
     * @var King23_Registry
     */
    private static $myInstance;

    /**
     * store for the global data
     * @var array
     */
    private $data = array();

    /**
     * private constructor, prevents from accidential instantiation
     */
    private function __construct()
    {
    }

    /**
     * Return Instance, create instance if not instanced yet
     * @return King23_Registry
     */
    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new King23_Registry();
        return self::$myInstance;
    }

    /**
     * magic get function, for conveniant access to registry
     * @param string $name
     * @return mixed
     */
    public function  __get($name)
    {
        if(isset($this->data[$name]))
            return $this->data[$name];
        return null;
    }

    /**
     * magic set function for conveniant access to registry
     * @param string $name
     * @param mixed $value
     * @return boolean
     */
    public function  __set($name,  $value)
    {
        return $this->data[$name] = $value;
    }
}

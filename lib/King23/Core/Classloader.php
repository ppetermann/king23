<?php
/*
 MIT License
 Copyright (c) 2010 - 2014 Peter Petermann

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
namespace King23\Core;

// its safe to assume that if King23_Singleton is not load yet those three classes need loading
// its also safe to assume that if it is load the other three are load as well.
if (!interface_exists('King23\Core\Singleton')) {
    require_once(pathinfo(__FILE__, PATHINFO_DIRNAME).'/Interfaces/Singleton.php');
    require_once(pathinfo(__FILE__, PATHINFO_DIRNAME).'/Exceptions/Exception.php');
    require_once(pathinfo(__FILE__, PATHINFO_DIRNAME).'/Exceptions/PathNotFoundException.php');
}

/**
 * classloader for King23, this class is meant to handle all classloading
 */
class Classloader implements \King23\Core\Interfaces\Singleton
{
    /**
     * Instance of the Classloader
     *
     * @var \King23\Core\Classloader
     */
    private static $myInstance;

    /**
     * should contain all found classes
     *
     * @var Array
     */
    private $classes = array();

    /**
     * If the autoloader was already registered
     *
     * @var boolean
     */
    protected static $registered = false;

    public static function register()
    {
        if (!self::$registered) {
            spl_autoload_register('\King23\Core\Classloader::load');
            self::$registered = true;
        }
    }

    /**
     * Unregister the autoloader if it has been
     * registered before
     *
     */
    public static function unregister()
    {
        if (self::$registered) {
            spl_autoload_unregister('\King23\Core\Classloader::load');
            self::$registered = false;
        }
    }

    /**
     * Singleton requirement, returns existing instance, creates new
     * instance if none exists
     *
     * @return \King23\Core\Classloader
     */
    public static function getInstance()
    {
        if (is_null(self::$myInstance)) {
            self::$myInstance = new \King23\Core\Classloader();
        }
        return self::$myInstance;
    }

    /**
     * Load the class definition for a specific class
     *
     * @param String $name
     * @return boolean
     */
    public static function load($name)
    {
        if ($file = self::getInstance()->find($name)) {
            require_once($file);
            return true;
        }
        return false;
    }

    /**
     * Parse given path for further classes
     *
     * @param String $path
     */
    public static function init($path)
    {
        self::getInstance()->configure($path);
    }

    /**
     * implementation of the parse call
     *
     * @param string $path
     * @throws Exceptions\PathNotFoundException
     */
    private function configure($path)
    {
        if (!file_exists($path) || !is_dir($path)) {
            throw new \King23\Core\Exceptions\PathNotFoundException;
        }
        $this->classes = array_merge($this->classes, $this->parseDir($path));
    }


    /**
     * builds a namesapce name out of a dir path, and a namespace piece
     * @param string $dir
     * @param string $namespace
     * @return string
     */
    private function buildNamespace($dir, $namespace) {
        if (empty($namespace)) {
            $namespace = '\\'; // global class
        } else {
            if ($namespace == '\\') {
                $namespace = "";
            }
            $namespace = $namespace.basename($dir).'\\';
        }
        return $namespace;
    }

    /**
     * recursive dir parsing method
     *
     * @param string $dir directory to parse
     * @param array $classes already found classes
     * @param string $namespace
     * @return array
     */
    private function parseDir($dir, $classes = array(), $namespace = "")
    {
        $namespace = $this->buildNamespace($dir, $namespace);

        $d = dir($dir);
        while ($item = $d->read()) {
            if ($item == "." || $item == "..") {
                continue;
            }

            if (is_dir("$dir/$item")) {
                $classes = array_merge($classes, $this->parseDir($dir."/".$item, array(), $namespace));
                continue;
            }

            if (substr($item, -4) == ".php") {
                $classes[$namespace.substr($item, 0, -4)] = "$dir/$item";
            }
        }
        return $classes;
    }

    /**
     * get a file name to a specified class name
     *
     * @param string $name name of the file
     * @return String filepath, false on fail
     */
    private function find($name)
    {
        if (isset($this->classes[$name])) {
            return $this->classes[$name];
        }
        return false;
    }
}

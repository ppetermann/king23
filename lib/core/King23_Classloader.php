<?php
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/exceptions/King23_Exception.php');
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/exceptions/King23_PathNotFoundException.php');

/**
 * classloader for King23, this class is meant to handle all classloading
 */
class King23_Classloader implements King23_Singleton
{
    /**
     * Instance of the Classloader
     * @var King23_Classloader
     */
    private static $myInstance;

    /**
     * should contain all found classes
     * @var Array
     */
    private $classes = array();

    /**
     * Singleton requirement, returns existing instance, creates new
     * instance if none exists
     * @return King23_Classloader
     */
    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new King23_Classloader();
        return self::$myInstance;
    }

    /**
     * Load the class definition for a specific class
     * @param String $name
     * @return boolean
     */
    public static function load($name)
    {
        if($file = self::getInstance()->find($name))
        {
            require_once($file);
            return true;
        }
        return false;
    }

    /**
     * Parse given path for further classes
     * @param String $path
     */
    public static function init($path)
    {
        self::getInstance()->configure($path);
    }

    /**
     * implementation of the parse call
     * @param string $path
     */
    private function configure($path)
    {
        if(!file_exists($path) || !is_dir($path))
            throw new King23_PathNotFoundException();
        $this->classes = array_merge($this->classes, $this->parseDir($path));
    }

    /**
     * recursive dir parsing method
     * @param string $dir directory to parse
     * @param array $classes allready found classes
     * @return array
     */
    private function parseDir($dir, $classes = array())
    {
        $d = dir($dir);
        while($item = $d->read())
        {
            if($item == "." || $item == "..")
                continue;

            if(is_dir("$dir/$item"))
            {
                $classes = array_merge($classes, $this->parseDir($dir . "/" . $item));
                continue;
            }

            if(substr($item, -4) == ".php")
            {
                $classes[substr($item, 0,-4)] = "$dir/$item";
            }
        }
        return $classes;
    }

    /**
     * get a file name to a specified class name
     * @param string $name name of the file
     * @return String filepath, false on fail
     */
    private function find($name)
    {
        if(isset($this->classes[$name]))
            return $this->classes[$name];
        return false;
    }
}
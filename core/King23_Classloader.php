<?php
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/exceptions/King23_Exception.php');
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/exceptions/King23_PathNotFoundException.php');
class King23_Classloader
{
    private static $myInstance;

    private $classes = array();

    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new King23_Classloader();
        return self::$myInstance;
    }
    public static function load($name)
    {
        if($file = self::getInstance()->find($name))
        {
            require_once($file);
            return true;
        }
        return false;
    }

    public static function init($path)
    {
        self::getInstance()->configure($path);
    }

    private function configure($path)
    {
        if(!file_exists($path) || !is_dir($path))
            throw new King23_PathNotFoundException();
        $this->classes = array_merge($this->classes, $this->parseDir($path));
    }

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

    private function find($name)
    {
        if(isset($this->classes[$name]))
            return $this->classes[$name];
        return false;
    }
}
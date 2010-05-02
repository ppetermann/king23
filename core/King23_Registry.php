<?php
class King23_Registry implements King23_Singleton
{
    private static $myInstance;
    private $data;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new King23_Registry();
        return self::$myInstance;
    }

    public function  __get($name)
    {
        if(isset($this->data[$name]))
            return $this->data[$name];
        return null;
    }

    public function  __set($name,  $value)
    {
        return $this->data[$name] = $value;
    }
}
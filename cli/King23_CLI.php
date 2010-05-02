<?php
final class King23_CLI implements King23_Singleton
{
    private static $myInstance;

    private function  __construct()
    {
    }
    
    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new King23_CLI();
    }

}

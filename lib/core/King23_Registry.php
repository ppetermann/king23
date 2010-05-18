<?php
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
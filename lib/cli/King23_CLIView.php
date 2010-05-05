<?php
abstract class King23_CLIView
{
    protected $log;

    public function __construct() // php! rofl!
    {
        if(isset(King23_Registry::getInstance()->cli_log))
            $this->log = King23_Registry::getInstance()->cli_log;
        else 
            $this->log = new King23_Log();
    }

    public function dispatch($action, $request)
    {
        if(!method_exists($this, $action))
            throw new King23_ViewActionDoesNotExistException();
        $this->$action($request);
    }

    protected function log($message) // conveniance
    {
        $this->log->log($message);
    }
}
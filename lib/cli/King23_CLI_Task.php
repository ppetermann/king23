<?php
/**
 * King23_CLI basis task, should be parent of all tasks
 */
abstract class King23_CLI_Task
{
    /**
     * list of available tasks (key) and their description (value)
     * @var Array
     */
    protected $tasks = array();

    /**
     * Name of the Task Module
     * @var String
     */
    protected $name = "MISSING NAME";

    
    /**
     * King23_CLI instance for conveniance
     * @var King23_CLI
     */
    protected $cli;


    /**
     * constructor, call parent::__construct from derived classes
     */
    public function __construct()
    {
        $this->cli = King23_CLI::getInstance();
    }


    /**
     * returns tasks array
     * @return array
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * print out info about the task module
     */
    public function info()
    {
        $this->cli->header("Module " . $this->name);
        $this->cli->header("-----------------------");
        $this->_info();
    }
    

    /**
     * might be called from info tasks to write basic information about each task
     */
    public function _info()
    {
        foreach($this->tasks as $name => $description)
        {
            $this->cli->header("Task: " . King23_CLI_OutputWriter::COLOR_FGL_Blue . $name);
            $this->cli->header("Description:");
            $this->cli->message($description);
            $this->cli->message();
        }
    }
}
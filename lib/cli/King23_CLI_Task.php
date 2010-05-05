<?php
class King23_CLI_Task
{
    protected $tasks = array();
    protected $name = "MISSING NAME";

    /**
     * @var King23_CLI
     */
    protected $cli;


    public function __construct()
    {
        $this->cli = King23_CLI::getInstance();
    }
    
    public function getTasks()
    {
        return $this->tasks;
    }

    public function info()
    {
        $this->cli->header("Module " . $this->name);
        $this->cli->header("-----------------------");
        $this->_info();
    }
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